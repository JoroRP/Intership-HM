<?php
declare(strict_types=1);
namespace GeorgiRadoslavov\Hm3new;

class Person
{
    private int $age;
    private string $name;

    public function __construct(int $age, string $name){
        $this->age = $age;
        $this->name = $name;
    }

    public function getAge(): int{
        return $this->age;
    }

    public function getName(): string{
        return $this->name;
    }

    public function setAge(int $age){
        $this->age = $age;
    }

    public function setName(string $name){
        $this->name = $name;
    }
}