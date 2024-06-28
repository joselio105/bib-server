<?php

namespace plugse\server\core\app\validation;

enum ValidationTypes: string
{
    case IS_REQUIRED = 'isRequired';
    case MUST_BE_INT = 'mustBeInt';
    case MUST_BE_BOOL = 'mustBeBool';
    case MUST_BE_STRING = 'mustBeString';
    case MUST_BE_PASSWORD = 'mustBePassword';
    case MUST_BE_PHONE = 'mustBePhone';
    case MUST_BE_EMAIL = 'mustBeEmail';
    case MUST_BE_URL = 'mustBeUrl';
    case MUST_BE_URL_LATTES = 'mustBeUrlLattes';
    case MUST_BE_URL_REPOUFSC = 'mustBeUrlRepoufsc';
    case MUST_BE_DATETIME = 'mustBeDatetime';
    case MUST_BE_DATE = 'mustBeDate';
    case MUST_BE_HOUR = 'mustBeHour';
    case MUST_BE_SEMESTER = 'mustBeSemester';
    case MUST_BE_AUTHORS = 'mustBeAuthors';
    case MUST_BE_CUTTER = 'mustBeCutter';
    case MUST_HAVE_LENGTH_EQUALS_TO = 'mustHaveLengthEqualsTo';
    case MUST_HAVE_LENGTH_GREATHER_THAN = 'mustHaveLengthGreatherThan';
    case MUST_HAVE_LENGTH_SMALLER_THAN = 'mustHaveLengthSmallerThan';
    case MUST_HAVE_LOWER_CASE_CHARS = 'mustHaveLowerCaseChars';
    case MUST_HAVE_UPPER_CASE_CHARS = 'mustHaveUpperCaseChars';
    case MUST_HAVE_NUMBERS = 'mustHaveNumbers';
    case MUST_HAVE_SPECIAL_CHARS = 'mustHaveSpecialChars';
}
