<?php
/**
 * @since     July 2017
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace CoreComponent\Entity;

class User
{
    protected $id;
    protected $name;
    protected $role;

    private function __construct($id, $name, $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->role = $role;
    }

    public static function createFromArray($data)
    {
        return new self($data['id'], $data['name'], Role::createFromArray($data['role']));
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->role->toArray()
        ];
    }
}
