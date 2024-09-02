<?php declare(strict_types=1);

namespace HM6;

class Subject
{
    private string $name;
    /** @var User[] */
    private array $teachers;
    /** @var User[] */
    private array $students;

    private array $grades;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->teachers = [];
        $this->students = [];
        $this->grades = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTeachers(): array
    {
        return $this->teachers;
    }

    public function setTeachers(array $teachers): void
    {
        $this->teachers = $teachers;
    }

    public function getStudents(): array
    {
        return $this->students;
    }

    public function setStudents(array $students): void
    {
        $this->students = $students;
    }

    public function getGrades(): array
    {
        return $this->grades;
    }

    public function setGrades(array $grades): void
    {
        $this->grades = $grades;
    }

    public function addGrade(string $username, float $grade): void
    {
        $this->grades[$username] = $grade;
    }

}