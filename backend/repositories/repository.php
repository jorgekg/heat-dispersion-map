<?php

abstract class Repository
{
  protected $table;
  protected $class;
  protected $database;

  public function __construct($class)
  {
    $this->class = $class;
    $instance = new ReflectionClass($this->class);
    $this->table = Utils::getAnnotation($instance->getDocComment(), '@table');
    $this->database = Utils::getAnnotation($instance->getDocComment(), '@database');
  }

  public function findById($id)
  {
    $prepare = (Database::instance($this->database))->prepare("SELECT * FROM {$this->table} WHERE id = ?");
    $prepare->bindValue(1, $id);
    $prepare->execute();
    return $prepare->fetch(PDO::FETCH_ASSOC);
  }

  public function list($page = 0, $size = 10, $filter = '')
  {
    $init = $size * $page;
    $finish = $size * ($page + 1);
    $prepare = (Database::instance($this->database))->prepare("SELECT * FROM {$this->table} {$filter} LIMIT {$init}, {$finish}");
    $prepare->execute();
    return $prepare->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insert($data)
  {
    $reflection = new ReflectionClass($this->class);
    $fields = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
    $processFields = array_filter(array_map(function ($field) {
      if ($field->name != 'id')
        return $field->name;
    }, $fields), function ($filter) {
      return !empty($filter);
    });
    $query = "INSERT INTO {$this->table} (";
    $query .= join(', ', $processFields);
    $query .= ') VALUES (' . join(', ', array_map(function ($field) {
      return '?';
    }, $processFields)) . ')';
    $prepare = (Database::instance($this->database))->prepare($query);
    $index = 1;
    foreach ($processFields as $key) {
      $prepare->bindValue($index, $data->$key);
      $index++;
    }
    $prepare->execute();
    return $this->findById((Database::instance($this->database))->lastInsertId());
  }

  public function delete($id)
  {
    $item = $this->findById($id);
    if (empty($item)) {
      throw new NotFoundException('This item not exists');
    }
    $prepare = (Database::instance($this->database))->prepare("DELETE FROM {$this->table} WHERE id = ?");
    $prepare->bindValue(1, $id);
    $prepare->execute();
  }

  public function update($id, $data)
  {
    $item = $this->findById($id);
    if (empty($item)) {
      throw new NotFoundException('This item not exists');
    }
    $reflection = new ReflectionClass($this->table);
    $fields = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

    $query = "UPDATE {$this->table} SET ";
    $query .= join(', ', array_filter(array_map(function ($field) use ($data) {
      $fd = $field->name;
      if ($field->name != 'id' && !empty($data->$fd))
        return $field->name . ' = ?';
    }, $fields), function ($field) {
      return !empty($field);
    }));
    $query .= " WHERE id = ?";
    $prepare = (Database::instance($this->database))->prepare($query);
    $index = 1;
    foreach ($fields as $field) {
      $field = $field->name;
      if ($field != 'id' && !empty($data->$field)) {
        $prepare->bindValue($index, $data->$field);
        $index++;
      }
    }
    $prepare->bindValue(count($fields), $id);
    $prepare->execute();
    return $this->findById($id);
  }
}
