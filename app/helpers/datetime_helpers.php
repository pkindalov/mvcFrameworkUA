<?php

function addDaysToCurrentDate($days)
{
    $days_str = '+' . $days . ' day';
    return (new \DateTime())->modify($days_str)->format('Y-m-d');
}

function addDaysToCurrentDateFormat($data)
{
    list('days' => $days, 'format' => $format) = $data;
    $days_str = '+' . $days . ' day';
    return (new \DateTime())->modify($days_str)->format($format);
}

function addDaysToDateAndFormat($data)
{
    list('days' => $days, 'date' => $date, 'format' => $format) = $data;
    $days_str = '+' . $days . ' day';
    return (new \DateTime($date))->modify($days_str)->format($format);
}

function getMonthsPeriodStr($end_month = '-5 months')
{
    $date_before_n_months = (new \DateTime())->modify($end_month)->format('Y-m-d');
    $start_month_num = intVal(date('m', strtotime($date_before_n_months)));
    $current_date = intVal(date('m'));
    return [$start_month_num, $current_date];
    // $months_arr = [];
    // for ($i = $start_month_num; $i <= $end_month_num; $i++) {
    //     $months_arr[] = $i;
    // }

    // return join(', ', $months_arr);
}
