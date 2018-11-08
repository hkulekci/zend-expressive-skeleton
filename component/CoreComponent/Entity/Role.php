<?php
/**
 * @since     July 2017
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace CoreComponent\Entity;

class Role
{
    protected $id;
    protected $name;

    private function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function createFromArray($data)
    {
        return new self($data['id'], $data['name']);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
