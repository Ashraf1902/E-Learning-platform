<?php

namespace App\Support;

use App\Models\Question;

/**
 * Single source of truth for interpreting quiz answers.
 *
 * Supports every question type:
 *   mcq          — dynamic 2..8 options; answer is a letter a..h or the full text
 *   true_false   — 'true' / 'false' (Arabic صح/خطأ accepted on input)
 *   fill_blank   — free text, diacritics/punctuation-insensitive match
 *   matching     — {leftIndex: rightText} pairs
 *   ordering     — array of items in the student's chosen order
 *   essay        — never auto-graded (admin grades it manually)
 *
 * Historic multiple-choice attempts can hold the same answer in several
 * shapes: latin letter ("b"), arabic letter ("ب"), or the full option text
 * ("Paris"). Everything is canonicalized before comparing, so a student
 * who genuinely picked the right option is never marked wrong.
 */
class AnswerChecker
{
    private const LETTER_MAP = [
        'a' => 'a', 'أ' => 'a', 'ا' => 'a', 'إ' => 'a', 'آ' => 'a',
        'b' => 'b', 'ب' => 'b',
        'c' => 'c', 'ج' => 'c',
        'd' => 'd', 'د' => 'd',
        'e' => 'e', 'ه' => 'e', 'هـ' => 'e',
        'f' => 'f', 'و' => 'f',
        'g' => 'g', 'ز' => 'g',
        'h' => 'h', 'ح' => 'h',
    ];

    private const TRUE_VALUES  = ['true', '1', 'yes', 'y', 'صح', 'صحيح', 'صواب', 'a'];
    private const FALSE_VALUES = ['false', '0', 'no', 'n', 'خطأ', 'غلط', 'خاطئ', 'b'];

    /**
     * Canonicalize a stored/given multiple-choice answer to 'a'..'h'.
     * Returns null when the value cannot be mapped to an option.
     */
    public static function canonical(mixed $given, Question $question): ?string
    {
        if ($given === null) {
            return null;
        }

        $value = mb_strtolower(trim((string) $given));

        if ($value === '') {
            return null;
        }

        if (isset(self::LETTER_MAP[$value])) {
            return self::LETTER_MAP[$value];
        }

        // Maybe the full option text was stored instead of its letter.
        foreach ($question->option_list ?? [] as $index => $text) {
            if (self::normalizeText($text) === self::normalizeText($value)) {
                return chr(97 + $index);
            }
        }

        return null;
    }

    public static function isCorrect(mixed $given, Question $question): bool
    {
        return match ($question->type) {
            'mcq'         => self::mcq($given, $question),
            'true_false'  => self::trueFalse($given, $question),
            'fill_blank'  => self::fillBlank($given, $question),
            'matching'    => self::matching($given, $question),
            'ordering'    => self::ordering($given, $question),
            default       => false, // essay — graded manually
        };
    }

    /**
     * Normalize a raw answer for storage in quiz_attempts.answers so that
     * reviews and regrades stay consistent.
     */
    public static function stored(mixed $given, Question $question): mixed
    {
        if ($given === null) {
            return null;
        }

        return match ($question->type) {
            'mcq'         => self::canonical($given, $question) ?? (is_string($given) ? trim($given) : $given),
            'true_false'  => self::toBool($given) === null ? null : (self::toBool($given) ? 'true' : 'false'),
            'fill_blank'  => is_string($given) ? trim($given) : $given,
            'matching'    => is_array($given) ? array_map(fn ($v) => trim((string) $v), $given) : null,
            'ordering'    => is_array($given) ? array_values(array_map(fn ($v) => trim((string) $v), $given)) : null,
            default       => is_string($given) ? trim($given) : $given,
        };
    }

    // ------------------------------------------------------------------
    // Type-specific graders
    // ------------------------------------------------------------------

    private static function mcq(mixed $given, Question $question): bool
    {
        $givenLetter   = self::canonical($given, $question);
        $correctLetter = self::canonical($question->correct_answer, $question);

        return $givenLetter !== null
            && $correctLetter !== null
            && $givenLetter === $correctLetter;
    }

    private static function trueFalse(mixed $given, Question $question): bool
    {
        $givenBool  = self::toBool($given);
        $correctBool = self::toBool($question->correct_answer);

        return $givenBool !== null && $correctBool !== null && $givenBool === $correctBool;
    }

    private static function fillBlank(mixed $given, Question $question): bool
    {
        if (!is_string($given) && !is_numeric($given)) {
            return false;
        }

        $normalized = self::normalizeText($given);

        if ($normalized === '') {
            return false;
        }

        foreach ($question->acceptedAnswers() as $answer) {
            if (self::normalizeText($answer) === $normalized) {
                return true;
            }
        }

        return false;
    }

    private static function matching(mixed $given, Question $question): bool
    {
        if (!is_array($given)) {
            return false;
        }

        $pairs = $question->pairs();

        if (count($pairs) === 0 || count($given) !== count($pairs)) {
            return false;
        }

        foreach ($pairs as $index => $pair) {
            $studentRight = $given[$index] ?? $given[(string) $index] ?? null;

            if ($studentRight === null || trim((string) $studentRight) === '') {
                return false;
            }

            if (self::normalizeText($studentRight) !== self::normalizeText($pair['right'])) {
                return false;
            }
        }

        return true;
    }

    private static function ordering(mixed $given, Question $question): bool
    {
        if (!is_array($given)) {
            return false;
        }

        $given    = array_values($given);
        $expected = $question->items();

        if (count($expected) === 0 || count($given) !== count($expected)) {
            return false;
        }

        foreach ($expected as $index => $item) {
            if (self::normalizeText($item) !== self::normalizeText($given[$index] ?? '')) {
                return false;
            }
        }

        return true;
    }

    // ------------------------------------------------------------------
    // Normalization helpers
    // ------------------------------------------------------------------

    /**
     * Aggressive text normalization for free-text answers:
     * strips diacritics/tatweel, folds alef/hamza/ta-marbuta variants,
     * drops punctuation and collapses whitespace.
     */
    public static function normalizeText(mixed $text): string
    {
        $value = mb_strtolower(trim((string) $text));

        // Remove tashkeel, superscript alef, tatweel
        $value = preg_replace('/[\x{064B}-\x{0652}\x{0670}\x{0640}]/u', '', $value) ?? $value;

        // Fold Arabic letter variants
        $value = strtr($value, [
            'أ' => 'ا', 'إ' => 'ا', 'آ' => 'ا', 'ٱ' => 'ا',
            'ة' => 'ه',
            'ى' => 'ي',
        ]);

        // Keep letters/digits only
        $value = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $value) ?? $value;

        return trim(preg_replace('/\s+/u', ' ', $value) ?? $value);
    }

    private static function toBool(mixed $value): ?bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if ($value === null) {
            return null;
        }

        $value = mb_strtolower(trim((string) $value));

        if ($value === '') {
            return null;
        }

        if (in_array($value, self::TRUE_VALUES, true)) {
            return true;
        }

        if (in_array($value, self::FALSE_VALUES, true)) {
            return false;
        }

        return null;
    }
}
