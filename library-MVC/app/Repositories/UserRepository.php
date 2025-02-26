<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Models\User;

class UserRepository{
    function __construct(
        private PDO $db,
    ) {

    }

    public function get_all_users($all_users=[]): array {
        try{
            $sql = 'SELECT id, name, email, isAdmin FROM users';

            $statement = $this->db->query($sql);
            $statement->execute();
            
            while ($user = $statement->fetch(PDO::FETCH_ASSOC)) {
                $all_users[] = new User(...$user);
            }

            return $all_users;

        } catch (PDOException $e) {
            die ($e->getMessage());
        }
    }

    public function register(array $inputs) {
        try {
            $sql = 'INSERT INTO users(name, email, password, isAdmin)
                    VALUES(:name, :email, :password, :isAdmin)';

            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':name' => $inputs['name'],
                ':email' => $inputs['email'],
                ':password' => password_hash($inputs['password'], PASSWORD_BCRYPT),
                ':isAdmin' => (int)false
            ]);
            
        } catch (PDOException $e) {
            die ($e->getMessage());
        }
    }

    public function login(array $inputs): bool {
        try {
            $sql = 'SELECT * FROM users
                    WHERE name=:name';
            
            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':name' => $inputs['name']
            ]);

            $user = $statement->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($inputs['password'], $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'isAdmin' => (bool) $user['isAdmin']
                ];
                return true;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            die ($e->getMessage());
        }
    }

    public function set_admin_status(int $id, int $new_admin_status) {
        try {
            $sql = 'UPDATE users
                    SET isAdmin=:isAdmin
                    WHERE id=:id';

            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':id' => $id,
                ':isAdmin' => $new_admin_status
            ]);

        } catch (PDOException $e) {
            die ($e->getMessage());
        }
    }

    public function delete_user(int $id) {
        try {
            $sql = 'DELETE FROM users
                    WHERE id=:id';

            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            die ($e->getMessage());
        }
    }

    public function edit_profile(int $id, array $inputs): bool {
        try {
            $sql = 'UPDATE users
                    SET name=:name, email=:email
                    WHERE id=:id';

            $statement = $this->db->prepare($sql);
            $result = $statement->execute([
                ':id' => $id,
                ':name' => $inputs['name'],
                ':email' => $inputs['email']
            ]);

            if ($result) {
                $_SESSION['user']['name'] = $inputs['name'];
                $_SESSION['user']['email'] = $inputs['email'];

                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die ($e->getMessage());
        }
    }

    public function change_password(int $id, string $old_password, string $new_password): bool {
        try{
            $sql = 'SELECT password FROM users
                    WHERE id=:id';

            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':id' => $id
            ]);

            $user = $statement->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($old_password, $user['password'])) {
                $sql = 'UPDATE users
                        SET password=:password
                        WHERE id=:id';

                $update_statement = $this->db->prepare($sql);
                return $update_statement->execute([
                    ':id' => $id,
                    ':password' => password_hash($new_password, PASSWORD_BCRYPT)
                ]);
            } else {
                return false;
            }
            
        } catch (PDOException $e) {
            die ($e->getMessage());
        }
    }
}