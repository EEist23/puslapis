<?php

class PasswordGenerator {
    public static function generate($length = 10) {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%&*'), 0, $length);
    }
}
