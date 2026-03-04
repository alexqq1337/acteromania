-- Migration: Add video support to hero section
-- Run this on existing database to add video columns

ALTER TABLE hero 
ADD COLUMN video_url TEXT AFTER image_url,
ADD COLUMN video_type ENUM('upload', 'youtube', 'vimeo') DEFAULT 'upload' AFTER video_url,
ADD COLUMN media_type ENUM('image', 'video') DEFAULT 'image' AFTER video_type;

-- Update existing record to use image as default
UPDATE hero SET media_type = 'image' WHERE media_type IS NULL;
