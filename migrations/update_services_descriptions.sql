-- Migration: Add short_description and full_description to services table
-- Run this file manually in phpMyAdmin or via command line

-- Rename existing description to short_description
ALTER TABLE services 
CHANGE COLUMN description short_description TEXT;

-- Add new full_description column
ALTER TABLE services 
ADD COLUMN full_description TEXT AFTER short_description;

-- Copy existing short_description to full_description as initial content
UPDATE services SET full_description = short_description WHERE full_description IS NULL;
