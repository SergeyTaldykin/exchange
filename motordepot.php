<?php

// Задача на взаимодействие между классами. Разработать систему «Автобаза».
// Диспетчер распределяет заявки на Рейсы между Водителями и назначает для этого Автомобиль.
// Водитель может сделать заявку на ремонт. Диспетчер может отстранить Водителя от работы.
// Водитель делает отметку о выполнении Рейса и состоянии Автомобиля.

/**
 * Заявка
 */
class Application
{
    protected bool $isDone = false;

    protected Car $car;
    protected ?Driver $driver;
    protected Trip $trip;

    public function __construct(Car $car, Driver $driver, Trip $trip)
    {
        $this->car = $car;
        $this->driver = $driver;
        $this->trip = $trip;

        $car->setApplication($this);
        $driver->setCar($car)->setApplication($this);
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function getTrip(): Trip
    {
        return $this->trip;
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function hasDriver(): bool
    {
        return (bool)$this->driver;
    }

    public function setDriver(?Driver $driver): void
    {
        $this->driver = $driver;
        if ($this->driver) {
            $driver->setCar($this->car)->setApplication($this);
        }
    }

    public function setDone(): void
    {
        $this->isDone = true;
    }
}

class Car
{
    protected ?Application $application = null;

    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function isBusy(): bool
    {
        return $this->application && !$this->application->isDone();
    }

    public function setApplication(Application $application): void
    {
        $this->application = $application;
    }

    public function setApplicationIsDone(): void
    {
        $this->application = null;
    }
}

class Dispatcher
{
    /**
     * @var Application[]
     */
    protected array $applications;

    /**
     * @var Trip[]
     */
    protected array $trips;
    protected MotorDepot $motorDepot;

    public function addTrip(Trip $trip): Dispatcher
    {
        $this->trips[] = $trip;
        return $this;
    }

    public function getTrip(): Trip
    {
        return array_shift($this->trips);
    }

    public function hasTrip(): bool
    {
        return (bool)$this->trips;
    }

    public function makeApplication(Trip $trip, Driver $driver, Car $car): Application
    {
        $application = new Application($car, $driver, $trip);
        $this->applications[] = $application;

        return $application;
    }

    public function setMotorDepot(MotorDepot $motorDepot)
    {
        $this->motorDepot = $motorDepot;
    }

    public function checkApplications(): void
    {
        foreach ($this->applications as $application) {
            if ($application->isDone() || $application->getDriver() && $application->getDriver()->checkApplicationIsDone()) {
                continue;
            }

            if ($application->hasDriver()) {
                if (mt_rand(0, 9) === 1) {
                    echo "driver takeawaycar: " . $application->getDriver()->name . "\n";

                    $application->getDriver()->takeAwayCar();
                }
            } else {
                if (mt_rand(0, 2) === 1) {
                    if ($driver = $this->motorDepot->requestDriver()) {
                        echo "driver takecar: " . $driver->name . "\n";

                        $application->setDriver($driver);
                    }
                }
            }
        }
    }

    public function getMotorDepot(): MotorDepot
    {
        return $this->motorDepot;
    }
}

class Driver
{
    protected ?Application $application = null;
    protected ?Car $car = null;

    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setCar(Car $car): Driver
    {
        $this->car = $car;

        return $this;
    }

    public function takeAwayCar(): void
    {
        $this->application->setDriver(null);
        $this->application = null;
        $this->car = null;
    }

    public function checkApplicationIsDone(): bool
    {
        if (mt_rand(0, 19) === 1) {
            echo "Application is done " . $this->application->getTrip()->name . "\n";
            $this->setApplicationIsDone();
            return true;
        }

        return false;
    }

    public function setApplicationIsDone(): void
    {
        $this->application->setDone();
        $this->application = null;
        $this->car->setApplicationIsDone();
    }

    public function setApplication(Application $application): void
    {
        $this->application = $application;
    }

    public function isBusy(): bool
    {
        return $this->application && !$this->application->isDone();
    }
}

class MotorDepot
{
    /**
     * @var Car[]
     */
    protected array $cars = [];

    /**
     * @var Dispatcher[]
     */
    protected array $dispatchers = [];

    /**
     * @var Driver[]
     */
    protected array $drivers = [];

    public function addCar(Car $car)
    {
        $this->cars[] = $car;
    }

    public function addDispatcher(Dispatcher $dispatcher)
    {
        $dispatcher->setMotorDepot($this);
        $this->dispatchers[] = $dispatcher;
    }

    public function addDriver(Driver $driver)
    {
        $this->drivers[] = $driver;
    }

    public function requestCar(): ?Car
    {
        foreach ($this->cars as $car) {
            if (!$car->isBusy()) {
                return $car;
            }
        }

        return null;
    }

    public function requestDriver(): ?Driver
    {
        foreach ($this->drivers as $driver) {
            if (!$driver->isBusy()) {
                return $driver;
            }
        }

        return null;
    }
}

/**
 * Рейс
 */
class Trip
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

$motorDepot = new MotorDepot();
$motorDepot->addCar(new Car('@'));
$motorDepot->addCar(new Car('#'));

$motorDepot->addDriver(new Driver('111'));
$motorDepot->addDriver(new Driver('222'));
$motorDepot->addDriver(new Driver('333'));

$motorDepot->addDispatcher($dispatcher1 = new Dispatcher());
$motorDepot->addDispatcher($dispatcher2 = new Dispatcher());

$dispatcher1->addTrip(new Trip('adfasdf11111'))
    ->addTrip(new Trip('adfasdf222222'))
    ->addTrip(new Trip('adfasdf333333'));

$dispatcher2->addTrip(new Trip('bvnvbnvbnv11111'))
    ->addTrip(new Trip('bvnvbnvbnv222222'))
    ->addTrip(new Trip('bvnvbnvbnv333333'))
    ->addTrip(new Trip('bvnvbnvbnv444444'));

for ($i = 0; ; $i++) {
    echo "$i\n";

    actions($dispatcher1);
    actions($dispatcher2);

    sleep(1);
}


function actions(Dispatcher $dispatcher1)
{
    if ($dispatcher1->hasTrip()) {
        if ($car = $dispatcher1->getMotorDepot()->requestCar()) {
            echo "has car: $car->name\n";
            if ($driver = $dispatcher1->getMotorDepot()->requestDriver()) {
                echo "has driver: $driver->name\n";
                $dispatcher1->makeApplication($dispatcher1->getTrip(), $driver, $car);
            }
        }
    }

    $dispatcher1->checkApplications();
}
