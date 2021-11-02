<?php
namespace Validator;

class Validator {
    /**
     * Validate the slug and return an errors array
     * If array is not empty user can show errors from the errors array
     * else can proceed to save the slug
     */
    public function validateSlug($slug) {
        $errors = array();

        // trim the whitespaces
        $slug = trim($slug);

        // check if full string is lowercase, if not show error
        if (strtolower($slug) != $slug) {
            $errors['slug'] = 'Slug must be lowercase.';
        }

        // check if string contains special characters other than hyphen
        if (preg_match('/[^A-Za-z0-9-]+/', $slug)) {
            $errors['slug'] = 'Slug must only contain alphanumeric characters and hyphens. No special characters allowed e.g. whitespaces, question marks, etc.';
        }

        // Check if string is not starting with hyphen
        if (substr($slug, 0, 1) == '-') {
            $errors['slug'] = 'Slug must not start with a hyphen.';
        }

        // Check if string is not ending with hyphen
        if (substr($slug, -1) == '-') {
            $errors['slug'] = 'Slug must not end with a hyphen.';
        }

        return $errors;
    }

}