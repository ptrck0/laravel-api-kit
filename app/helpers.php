<?php

if (! function_exists('array_consists_of_integers')) {
    /**
     * @param  array  $items
     * @return bool
     */
    function array_consists_of_integers(array $items): bool
    {
        foreach ($items as $item) {
            if (!is_int($item)) {
                return false;
            }
        }

        return true;
    }
}
