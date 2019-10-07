<?php

require_once __DIR__ . '/../configs/database.php';

function readFiles($dir)
{
    $database = '';
    $files = glob($dir . '/*');
    foreach ($files as $file) {
        if (is_dir($file)) {
            readFiles($file);
        } else {
            foreach (explode('/', $file) as $classFile) {
                if (strpos($classFile, '.model.php') !== false) {
                    require_once($file);
                    $class = explode('.', $classFile)[0];
                    $instance = new ReflectionClass($class);
                    $db = (new Database($database))->instance();
                    if (!empty(getAnnotation($instance->getDocComment(), '@database'))) {
                        $database = getAnnotation($instance->getDocComment(), '@database');
                    }
                    $table = getAnnotation($instance->getDocComment(), '@table');
                    if (!empty($table)) {
                        $tableName = $db->query('SELECT * 
                                        FROM information_schema.tables
                                        WHERE table_schema = "' . $database . '" 
                                        AND table_name = "' . $table . '"')
                                        -> fetch(PDO::FETCH_ASSOC);
                        if (empty($tableName)) {
                            echo 'Criando a tabela = ' . $database . '.' . $table . '' . chr(10);
                            $createTable = 'CREATE TABLE ' . $table . ' (';
                            foreach ($instance->getProperties() as $property) {
                                $createTable .= createField($property) . ', ';
                            }
                            foreach ($instance->getProperties() as $property) {
                                $createTable .= primaryKey($property);
                            }
                            echo $createTable .= ')';
                            $db->exec($createTable);
                            echo 'Tabela ' . $table . ' criada com sucesso';
                        } else {
                            
                        }
                    }
                }
            }
        }
    }
}

function createField($property)
{
    $type = getAnnotation($property->getDocComment(), '@type');
    $auto = getAnnotation($property->getDocComment(), '@auto');
    $nullable = getAnnotation($property->getDocComment(), '@nullable');
    $max = getAnnotation($property->getDocComment(), '@max');
    $decimal = getAnnotation($property->getDocComment(), '@decimal');
    return $property->getName() . ' '
        . $type . (!empty($max) ? '(' . $max . (!empty($decimal) ? ' , ' . $decimal : '') . ')' : '')
        . ($auto == 'true' ? ' auto_increment ' : ' ')
        . ($nullable == 'true' ? ' null ' : ' not null ');
}

function primaryKey($property)
{
    $id = getAnnotation($property->getDocComment(), '@id');
    if ($id == 'true') {
        return ' PRIMARY KEY (' . $property->getName() . ')';
    }
    return '';
}

function getAnnotation($annotations, $annotation)
{
    foreach (explode(',', $annotations) as $str) {
        if (strpos($str, $annotation)) {
            return str_replace('=', '', str_replace(' ', '', str_replace($annotation, '', str_replace('*/', '', str_replace('/**', '', $str)))));
        }
    }
}

echo 'Iniciando o script de migração ' . chr(10);
try {
    readFiles(__DIR__ . '/../models');
} catch (Exception $e) {
    print_r($e->getMessage());
}
echo 'Terminado o script de migração ' . chr(10);