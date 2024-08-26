<?php declare(strict_types=1);

namespace HM4;

//require __DIR__ . '/Subject.php';
//require __DIR__ . '/User.php';

use function Sodium\add;

class MainPanel
{
    /** @var Subject[] */
    public array $Subjects = [];

    /** @var User[] */
    public array $Users = [];

    public function __construct()
    {
    }


    public function login()
    {
        $adminUser = new User('admin', 'admin123', 'admin', 'Admin Administratov');
        $this->Users[] = $adminUser;

        for ($i = 3; $i > 0; $i--) {

            $username = readline("Enter username- ");
            $password = readline("Enter password- ");

            if ($adminUser->getUsername() == $username && $adminUser->verifyPassword($password)) {
                echo "Login successful. Welcome, $username!\n";
                $this->adminMenu();

            } else if ($i > 0) {
                echo "Invalid username or password.\nYou have " . ($i - 1) . " tries left.\n";
            }
        }
        echo "You have made too many incorrect attempts to log in.";
        exit();
    }

    public function adminMenu(): void
    {
        echo "\nAdmin Panel\n";
        echo "1. Create a subject\n";
        echo "2. Create a teacher\n";
        echo "3. Create a student\n";
        echo "4. Remove a subject\n";
        echo "5. Remove a teacher\n";
        echo "6. Remove a student\n";
        echo "7. Log out\n";

        $choice = readline("Please select an option - ");

        switch ($choice) {
            case '1':
                $this->createSubject();
                break;

            case '2':
                if (empty($this->Subjects)) {
                    echo "Please create at least one subject first!\n";
                    $this->adminMenu();
                } else {
                    $this->userCreator("teacher");
                }
                break;

            case '3':
                if (empty($this->Subjects)) {
                    echo "Please create at least one subject first!\n";
                    $this->adminMenu();
                } else {
                    $this->userCreator("student");
                }
                break;

            case '4':
                $this->removeSubject();
                break;

            case '5':
                $this->removeUser('teacher');
                break;

            case '6':
                $this->removeUser('student');
                break;

            case '7':
                echo "Logging out.\n";
                //$this->login();
                exit();
                break;

            default:
                echo "Invalid choice. Please try again.\n\n";
                $this->adminMenu();
        }
    }


    public function createSubject(): void
    {
        while (true) {
            $name = readline("Please enter a name for the subject - ");

            if (strlen(trim($name)) > 1) {
                $subject = new Subject($name);
                $this->Subjects[] = $subject;
                echo "Subject " . $subject->getName() . " was created successfully.\n";
                $this->adminMenu();

            } else if (strlen(trim($name)) > 0) {
                echo "Subject name cannot be shorter than 2 symbols. Please enter a valid name.\n";
            } else {
                echo "Subject name cannot be blank. Please enter a valid name.\n";
            }
        }
    }

    public function userCreator($role)
    {
        do {
            $username = readline("Please enter a username for the $role - ");
            if (strlen(trim($username)) == 0) {
                echo "Username cannot be blank. Please enter a valid username.\n";
            }
        } while (strlen(trim($username)) == 0);

        do {
            $password = readline("Please enter a password for the $role - ");
            if (strlen(trim($password)) == 0) {
                echo "Username cannot be blank. Please enter a valid username.\n";
            }
        } while (strlen(trim($password)) == 0);

        do {
            $name = readline("Please enter a name for the $role - ");
            if (strlen(trim($name)) == 0) {
                echo "The name cannot be blank. Please enter a valid name.\n";
            }
        } while (strlen(trim($name)) == 0);

        echo "Available subjects:\n";
        foreach ($this->Subjects as $subject) {
            echo "- " . $subject->getName() . "\n";
        }

        $selectedSubjects = [];

        do {
            $subjectInput = readline("Enter the names of the subjects to assign to the $role, separated by commas- ");
            $subjectNames = array_map('trim', explode(',', $subjectInput));

            foreach ($subjectNames as $subjectName) {
                $foundSubject = null;
                foreach ($this->Subjects as $subject) {
                    if (strcasecmp($subject->getName(), $subjectName) === 0) {
                        $foundSubject = $subject;
                        break;
                    }
                }

                if ($foundSubject && !in_array($foundSubject, $selectedSubjects, true)) {
                    $selectedSubjects[] = $foundSubject;
                } else if (!$foundSubject && !empty($subjectInput)) {
                    echo "Subject " . $subjectName . " not found. Please try again.\n";
                } else {
                    echo "You must assign at least one valid subject to the $role.\n";
                }
            }

        } while (empty($selectedSubjects));

        $newUser = new User($username, $password, $role, $name);
        $this->Users[] = $newUser;

        if ($role == "teacher") {
            foreach ($selectedSubjects as $subject) {
                $subject->setTeachers(array_merge($subject->getTeachers(), [$newUser]));
            }
        } else if ($role == "student") {
            foreach ($selectedSubjects as $subject) {
                $subject->setStudents(array_merge($subject->getStudents(), [$newUser]));
            }
        }


        echo ucfirst($role) . " " . $newUser->getName() . " with username " . $newUser->getUsername() . " was created successfully and assigned to the following subjects:\n";
        foreach ($selectedSubjects as $subject) {
            echo "- " . $subject->getName() . "\n";
        }
        $this->adminMenu();
    }

    public function removeSubject(): void
    {
        if (empty($this->Subjects)) {
            echo "No subjects available to remove.\n";
            $this->adminMenu();
        }

        echo "Available subjects:\n";
        foreach ($this->Subjects as $subject) {
            echo "- " . $subject->getName() . "\n";
        }

        subjectCycle:
        $subjectNameInput = readline("Enter the name of the subject you want to remove - ");
        $subjectName = trim($subjectNameInput);

        if (strlen($subjectName) === 0) {
            echo "Subject name cannot be blank. Please enter an existing subject name.\n";
            goto subjectCycle;
        }

        $subjectIndex = null;
        foreach ($this->Subjects as $index => $subject) {
            if (strcasecmp($subject->getName(), $subjectName) === 0) {
                $subjectIndex = $index;
                break;
            }
        }

        if ($subjectIndex !== null) {
            $removedSubject = $this->Subjects[$subjectIndex];
            unset($this->Subjects[$subjectIndex]);
            $this->Subjects = array_values($this->Subjects);

            echo "Subject " . $removedSubject->getName() . " was removed successfully.\n";
            $this->adminMenu();
        } else {
            echo "Subject with the name " . $subjectName . " not found. Please try again.\n";
            goto subjectCycle;
        }

    }

    public function removeUser($role): void
    {
        if (empty($this->Users)) {
            echo "No " . $role . "s" . " available to remove.\n";
            $this->adminMenu();
        }

        echo "Available " . $role . "s:\n";
        foreach ($this->Users as $user) {
            if ($user->getRole() == $role) {
                echo '- ' . $user->getName() . " with username " . $user->getUsername() . "\n";
            }
        }

        userCycle:
        $usernameInput = readline("Enter the username of the  you want to remove - ");
        $usernameToRemove = trim($usernameInput);


        if (strlen($usernameToRemove) == 0) {
            echo "Username cannot be blank. Please enter an existing username.\n";
            goto userCycle;
        }

        $userIndex = null;
        foreach ($this->Users as $index => $user) {
            if ($user->getRole() == $role) {
                if (strcasecmp($user->getName(), $usernameToRemove) === 0) {
                    $userIndex = $index;
                    break;
                }
            }

        }

        if ($userIndex !== null) {
            $removedUser = $this->Users[$userIndex];
            unset($this->Users[$userIndex]);
            $this->Users = array_values($this->Users);

            echo "Username " . $removedUser->getUsername() . " was removed successfully.\n";
        } else {
            echo "Username " . $usernameToRemove . " not found. Please try again.\n";
            goto userCycle;
        }

        if ($role == 'teacher') {
            foreach ($this->Subjects as $subject) {
                $allTeachers = $subject->getTeachers();

                foreach ($allTeachers as $index => $teacher) {
                    if ($teacher->getUsername() === $removedUser->getUsername()) {
                        unset($allTeachers[$index]);
                        break;
                    }
                }
                $subject->setTeachers($allTeachers);
            }
        } else if ($role == 'student') {
            foreach ($this->Subjects as $subject) {
                $allStudents = $subject->getStudents();

                foreach ($allStudents as $index => $student) {
                    if ($student->getUsername() === $removedUser->getUsername()) {
                        unset($allStudents[$index]);
                        break;
                    }
                }
                $subject->setStudents($allStudents);
            }
        }

        $this->adminMenu();
    }


}