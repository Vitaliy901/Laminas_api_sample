<?php
// ENV
function env(string $key, string $value = null) {
    return array_key_exists($key, $_ENV) ? $_ENV[$key]: $value;
};