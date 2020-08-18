$.datepicker.regional[ "ru" ] = $.datepicker.regional[ "" ];
$.datepicker.regional[ "ru" ] = { // Default regional settings
    closeText: "Продолжить", // Display text for close link
    prevText: "Пред.", // Display text for previous month link
    nextText: "След.", // Display text for next month link
    currentText: "Сегодня", // Display text for current month link
    monthNames: [ "Январь","Февраль","Март","Апрель","Май","Июнь",
        "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь" ], // Names of months for drop-down and formatting
    monthNamesShort: [ "Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек" ], // For formatting
    dayNames: [ "Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота" ], // For formatting
    dayNamesShort: [ "Вск", "Пон", "Втр", "Срд", "Чтв", "Птн", "Сбт" ], // For formatting
    dayNamesMin: [ "Вс","Пн","Вт","Ср","Чт","Пт","Сб" ], // Column headings for days starting at Sunday
    weekHeader: "Неделя", // Column header for week of the year
    dateFormat: "dd.mm.yy", // See format options on parseDate
    firstDay: 1, // The first day of the week, Sun = 0, Mon = 1, ...
    isRTL: false, // True if right-to-left language, false if left-to-right
    showMonthAfterYear: false, // True if the year select precedes month, false for month then year
    yearSuffix: "" // Additional text to append to the year in the month headers
};
