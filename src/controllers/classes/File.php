<?php

class File {
    public static function read($path) {
        if (file_exists($path)) {
            return file_get_contents($path);
        }
        return false;
    }

    public static function write($path, $content) {
        return file_put_contents($path, $content);
    }

    public static function delete($path) {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    public static function copy($source, $destination) {
        if (file_exists($source)) {
            return copy($source, $destination);
        }
        return false;
    }

    public static function move($source, $destination) {
        if (file_exists($source)) {
            return rename($source, $destination);
        }
        return false;
    }
}
?>