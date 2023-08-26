<?php

namespace App\Database;

use App\Exceptions\GalleryException;
use App\Facades\Response;
use Exception;
use PDO;

class Gallery extends Database
{
    protected static string $all_gallery = "SELECT * FROM galleries;";
    protected static string $get_by_id = "SELECT * FROM galleries WHERE `id` = {id};";
    protected static string $delete_gallery = "DELETE FROM galleries WHERE `id` = {id};";
    protected static string $create_gallery = "INSERT INTO galleries ({columns}) VALUES ({values});";
    protected static string $update_gallery = "UPDATE galleries SET {sets} WHERE `id` = {id};";


    public function __construct()
    {
        parent::__construct();
    }

    public static function all(): array|bool
    {
        new self;
        $sql = self::$all_gallery;

        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return GalleryException::error($e->getMessage());
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_by_id(int $id): array|bool
    {
        $sql = self::setId(self::$get_by_id, $id);

        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return GalleryException::error($e->getMessage());
        }

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete(int $id): string
    {
        new self;
        $sql = self::$delete_gallery;
        $sql = self::setId($sql, $id);

        $stmt = self::$db->prepare($sql);

        try {
            if ($stmt->execute()) return Response::success("Galeria deletada com sucesso");
        } catch (Exception $e) {
            return GalleryException::error($e->getMessage());
        }
    }

    public static function create(array $data): string
    {
        new self;
        unset($data["id"]);
        $sql = self::$create_gallery;
        $sql = self::setColumns($sql, array_keys($data));
        $sql = self::setValues($sql, array_values($data));

        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return GalleryException::error($e->getMessage());
        }

        return Response::success("Galeria criada com sucesso!");
    }

    public static function update(int $id, array $data): array|bool
    {
        new self;

        $sql = self::setId(self::$update_gallery, $id);
        $sql = self::setParams($sql, $data);


        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return GalleryException::error($e->getMessage());
        }

        return self::get_by_id($id);
    }
}
