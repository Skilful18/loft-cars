<?php

trait Gps
{
    // Gps в салон - 15 рублей в час, минимум 1 час. Округление в большую сторону
    protected $gps = 15;

    public function gpsToSalon()
    {
        $gpsTime = ceil($this->gps / 60);
        $this->gps *= $gpsTime;
        return $this->gps;
    }
}

trait Drive
{
    // Дополнительный водитель - 100 рублей единоразово
    public function additionalDriver($sum)
    {
        return $sum += 100;
    }
}

// 1. Создать интерфейс, который будет содержать описание метода подсчета цены, метода добавления услуги (принимает на вход интерфейс услуги)

interface iService
{
    // свойства - переменные

    //методы или функции
    public function priceCalculationMethod ($km, $min); // метод подсчета цены // это и есть move
    // public function serviceAddingMethod (); // метод добавления услуги
    // public function additionalServices ();

}

// 3. Реализовать абстрактный класс тарифа, который будет описывать основные методы и имплементировать описанный в п.1 интерфейс

abstract class tariff implements iService
{
    protected $km;
    protected $min;

//    public function serviceAddingMethod ()
//    {
//        // принимает на вход интерфейс услуги
//        return ''; // сделать это через трейты и добавлять потом в тарифы
//    }
    // 2. Описать интерфейс доп. услуги, который содержит метод применения услуги к тарифу, который пересчитывает цену в зависимости от особенностей услуги
//    public function additionalServices ()
//    {
//        // gps тоже в трейте от абстрактного класса к тарифам.
//    }
}

// 4. Все тарифы должны наследоваться от абстрактного тарифа из п.2

class basicTariff extends tariff
{
    protected $km = 10;
    protected $min = 3;

    use Gps;
    use Drive;

    function priceCalculationMethod($km, $min)
    {
        $this->km *= $km;
        $this->min *= $min;
        $sum = $this->km + $this->min;
        $gps = $this->additionalDriver($sum) + $this->gpsToSalon();
        return 'Вы проехали ' . $km . ' км за ' . $min .
            ' мин. Общая сумма = ' . $sum . ' рублей' .
            ', а с вызовом водителя будет стоить ' . $this->additionalDriver($sum) . ' рублей' .
            ', а если вы хотите еще gps то ' . $gps . ' рублей';
    }
}

$basic = new basicTariff();
echo $basic->priceCalculationMethod(5, 5);
echo "<br>";


class hourlyRate extends tariff
{
    protected $km = 0;
    protected $rub = 200;

    use Gps;
    use Drive;

    function priceCalculationMethod($km, $min)
    {
        $hour = ceil($min / 60);

        $this->km *= $km;
        $this->rub *= $hour;
        $sum = $this->km + $this->rub;
        $gps = $this->additionalDriver($sum) + $this->gpsToSalon();

        return 'Вы проехали ' . $min . ' мин. Общая сумма = ' . $sum . ' рублей,' .
            ' а с вызовом водителя будет стоить '. $this->additionalDriver($sum) . ' рублей' .
            ', а если вы хотите еще gps то ' . $gps . ' рублей';
    }
}

$hourly = new hourlyRate();
echo $hourly->priceCalculationMethod(0, 1);
echo "<br>";

class studentTariff extends tariff
{
    protected $km = 4;
    protected $min = 1;

    use Gps;
    use Drive;

    function priceCalculationMethod($km, $min)
    {
        $this->km *= $km;
        $this->min *= $min;
        $sum = $this->km + $this->min;
        $gps = $this->additionalDriver($sum) + $this->gpsToSalon();

        return 'Вы проехали ' . $km . ' км за ' . $min .
            ' мин. Общая сумма = ' . $sum . ' рублей' .
            ', а с вызовом водителя будет стоить ' . $this->additionalDriver($sum) . ' рублей' .
            ', а если вы хотите еще gps то ' . $gps . ' рублей';
    }
}

$student = new studentTariff();
echo $student->priceCalculationMethod(4, 1);

// 5. Описать 2 услуги реализовав интерфейс услуг

// Пример вызова:
//1. Тариф базовый(5 км, 1 час)
//- добавить услугу GPS
//
//    = 5км * 10 руб / км + 60 мин * 3 руб / мин + 15 руб / час * 1 час = 50 + 180 + 15 = 245
