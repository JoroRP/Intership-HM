<?php declare(strict_types=1);

class Teacher
{
    private string $id;
    private string $name;
    private string $subject;
    private string $email;

    /**
     * @param string $id
     * @param string $name
     * @param string $subject
     * @param string $email
     */
    public function __construct(string $id, string $name, string $subject, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->subject = $subject;
        $this->email = $email;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


}