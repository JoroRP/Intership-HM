<?php declare(strict_types=1);

namespace HM6;

use HM6\User;
use HM6\Subject;

class MainPanel
{
    /** @var Subject[] */
    public array $Subjects = [];

    /** @var User[] */
    public array $Users = [];

    public function __construct()
    {
    }

    public function loadUsers(): void
    {

        if (file_exists("users.txt")) {
            $data = file_get_contents("users.txt");
            $usersArray = unserialize($data, ['allowed_classes' => [User::class]]);
            $this->Users = is_array($usersArray) ? $usersArray : [];
        } else {
            $this->Users = [];
        }
    }

    public function getUsers(): array
    {
        return $this->Users;
    }

    public function saveUsers(): void
    {
        $data = serialize($this->Users);

        $result = file_put_contents("users.txt", $data);

        if ($result === false) {
            echo '<div class="alert alert-danger">Error saving users. Please try again.</div>';
        }
    }

    public function addAdmin(): void
    {
        $adminUser = new User('admin', 'admin123', 'admin', 'Admin Administratov');
        $subject = new Subject("History");
        $subject1 = new Subject("Maths");
        foreach ($this->Users as $user) {
            if (strcasecmp($user->getUsername(), $adminUser->getUsername()) === 0) {
                return;
            }
        }
        $this->Users[] = $adminUser;
        $this->saveUsers();

        $this->Subjects[] = $subject;
        $this->Subjects[] = $subject1;
        $this->saveSubjects();
    }

    public function authenticateUser(string $username, string $password): bool
    {
        foreach ($this->Users as $user) {
            if (strcasecmp($user->getUsername(), $username) === 0) {
                if ($user->verifyPassword($password)) {
                    $_SESSION['username'] = $user->getUsername();
                    $_SESSION['role'] = $user->getRole();
                    $_SESSION['name'] = $user->getName();
                    return true;
                }
            }
        }
        return false;
    }

    public function loadSubjects(): void
    {
        if (file_exists("subjects.txt")) {
            $data = file_get_contents("subjects.txt");
            $subjectsArray = unserialize($data);
            $this->Subjects = is_array($subjectsArray) ? $subjectsArray : [];
        }
    }

    public function getSubjects(): array
    {
        return $this->Subjects;
    }

    public function saveSubjects(): void
    {
        $data = serialize($this->Subjects);
        $result = file_put_contents("subjects.txt", $data);

        if ($result === false) {
            echo '<div class="alert alert-danger">Error saving the subjects. Please try again.</div>';
        }
    }

    public function createSubject(): void
    {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject_name'])) {
            $name = trim($_POST['subject_name']);

            if (strlen($name) > 1) {
                $subjectExists = array_filter($this->Subjects, function ($subject) use ($name) {
                    return $subject->getName() === $name;
                });

                if (!empty($subjectExists)) {
                    $message = '<div class="alert alert-warning">Subject "' . htmlspecialchars($name) . '" already exists. Please enter a different name.</div>';
                    $_SESSION['admin_message'] = $message;
                    return;
                }

                $subject = new Subject($name);
                $this->Subjects[] = $subject;
                $this->saveSubjects();
                $message = '<div class="alert alert-success">Subject ' . htmlspecialchars($subject->getName()) . ' was created successfully.</div>';
            } elseif (strlen($name) > 0) {
                $message = '<div class="alert alert-warning">Subject name cannot be shorter than 2 symbols. Please enter a valid name.</div>';
            } else {
                $message = '<div class="alert alert-warning">Subject name cannot be blank. Please enter a valid name.</div>';
            }
        }
        $_SESSION['admin_message'] = $message;
    }

    public function createUser(string $role): void
    {
        $message = '';

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $subjectNames = $_POST['subjects'] ?? [];

        if (strlen($username) == 0) {
            $message = '<div class="alert alert-warning">Username cannot be blank. Please enter a valid username.</div>';
            $_SESSION['admin_message'] = $message;
            return;
        }

        if (strlen($password) == 0) {
            $message = '<div class="alert alert-warning">Password cannot be blank. Please enter a valid password.</div>';
            $_SESSION['admin_message'] = $message;
            return;
        }

        if (strlen($name) == 0) {
            $message = '<div class="alert alert-warning">Name cannot be blank. Please enter a valid name.</div>';
            $_SESSION['admin_message'] = $message;
            return;
        }

        $this->loadUsers();

        $userExists = array_filter($this->Users, function ($user) use ($username) {
            return $user->getUsername() === $username;
        });

        if (!empty($userExists)) {
            $message = '<div class="alert alert-warning">Username "' . htmlspecialchars($username) . '" already exists. Please choose a different username.</div>';
            $_SESSION['admin_message'] = $message;
            return;
        }

        $selectedSubjects = [];
        foreach ($subjectNames as $subjectName) {
            foreach ($this->Subjects as $subject) {
                if (strcasecmp($subject->getName(), $subjectName) === 0) {
                    $selectedSubjects[] = $subject;
                    break;
                }
            }
        }

        if (empty($selectedSubjects)) {
            $message = '<div class="alert alert-warning">You must assign at least one valid subject to the ' . htmlspecialchars($role) . '.</div>';
            $_SESSION['admin_message'] = $message;
            return;
        }

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

        $this->saveUsers();
        $this->saveSubjects();

        $message = '<div class="alert alert-success">' . ucfirst($role) . ' "' . htmlspecialchars($newUser->getName()) . '" with username "' . htmlspecialchars($newUser->getUsername()) . '" was created successfully and assigned to the following subjects:</div>';
        foreach ($selectedSubjects as $subject) {
            $message = $message . '<div class="alert alert-info">- ' . htmlspecialchars($subject->getName()) . '</div>';
        }
        $_SESSION['admin_message'] = $message;
    }

    public function removeSubject(): void
    {
        $message = '';

        $this->loadSubjects();
        $this->loadUsers();

        $subjectName = trim($_POST['subject_name'] ?? '');

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

            $connectedTeachers = $removedSubject->getTeachers();
            $connectedStudents = $removedSubject->getStudents();


            $this->Users = array_filter($this->Users, function ($user) use ($connectedTeachers) {
                return !in_array($user, $connectedTeachers, true);
            });


            $this->Users = array_filter($this->Users, function ($user) use ($connectedStudents) {
                return !in_array($user, $connectedStudents, true);
            });

            $this->Users = array_values($this->Users);

            $this->saveSubjects();
            $this->saveUsers();

            $message = '<div class="alert alert-success">Subject "' . htmlspecialchars($removedSubject->getName()) . '" was removed successfully.</div>';
        } else {
            $message = '<div class="alert alert-warning">Subject with the name "' . htmlspecialchars($subjectName) . '" not found. Please try again.</div>';
        }

        $_SESSION['admin_message'] = $message;
    }

    public function removeUser(string $role): void
    {
        $message = '';

        $role = strtolower($role);

        $usernameToRemove = $_POST['username'] ?? '';
        if (empty($usernameToRemove)) {
            $_SESSION['admin_message'] = "Username cannot be blank.";
            return;
        }

        $this->loadUsers();

        $userIndex = null;
        foreach ($this->Users as $index => $user) {
            if ($user instanceof User && $user->getRole() === $role && strcasecmp($user->getUsername(), $usernameToRemove) === 0) {
                $userIndex = $index;
                break;
            }
        }

        if ($userIndex !== null) {
            $removedUser = $this->Users[$userIndex];
            unset($this->Users[$userIndex]);
            $this->Users = array_values($this->Users);

            $this->updateSubjects($removedUser, $role);
            $this->saveUsers();

            $message = "Username " . htmlspecialchars($removedUser->getUsername()) . " was removed successfully.";
        } else {
            $message = "Username " . htmlspecialchars($usernameToRemove) . " not found.";
        }

        $_SESSION['admin_message'] = $message;
    }

    private function updateSubjects(User $removedUser, string $role): void
    {
        foreach ($this->Subjects as $subject) {
            if ($role === 'teacher') {
                $teachers = $subject->getTeachers();
                $teachers = array_filter($teachers, function (User $teacher) use ($removedUser) {
                    return $teacher->getUsername() !== $removedUser->getUsername();
                });
                $subject->setTeachers(array_values($teachers));
            } else if ($role === 'student') {
                $students = $subject->getStudents();
                $students = array_filter($students, function (User $student) use ($removedUser) {
                    return $student->getUsername() !== $removedUser->getUsername();
                });
                $subject->setStudents(array_values($students));
            }
        }

        $this->saveSubjects();
    }

    public function gradeStudent(string $subjectName, string $username, int $grade): void
    {
        $message = '';

        foreach ($this->Subjects as $subject) {
            if ($subject->getName() === $subjectName) {
                $students = array_map(fn($student) => $student->getUsername(), $subject->getStudents());

                if (in_array($username, $students, true)) {
                    $currentGrades = $subject->getGrades();

                    if (!isset($currentGrades[$username])) {
                        $currentGrades[$username] = [];
                    } elseif (!is_array($currentGrades[$username])) {
                        $currentGrades[$username] = [$currentGrades[$username]];
                    }

                    $currentGrades[$username][] = $grade;

                    $subject->setGrades($currentGrades);

                    $this->saveSubjects();

                    $message = '<div class="alert alert-success">Grade has been added successfully.</div>';
                    $_SESSION['admin_message'] = $message;
                } else {
                    $message = '<div class="alert alert-danger">Student is not assigned to the selected subject.</div>';
                    $_SESSION['admin_message'] = $message;
                }
                return;
            }
        }

        $message = '<div class="alert alert-danger">Subject not found.</div>';
        $_SESSION['admin_message'] = $message;
    }


}