<?php

namespace ErrorHandler;
class UserErrorMessages
{
    const EMPTY_TITLE_OR_AUTHOR = "Назва або автор не можуть бути порожніми";
    const INVALID_PRICE = "Ціна повинна бути більшою за 0";
    const NEGATIVE_QUANTITY = "Кількість не може бути від’ємною";
    const ISBN_INVALID = "ISBN має бути int";
    const ISBN_ALREADY_EXISTS="ISBN вже існує";
    const INVALID_QUANTITY="Невірна кількість";
    const BOOK_NOT_FOUND = "Книга не знайдена";
    const NOT_ENOUGH_QUANTITY = "Недостатньо примірників";
}
