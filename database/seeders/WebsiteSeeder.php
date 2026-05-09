<?php

namespace Database\Seeders;

use App\Models\Website;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    /**
     * Seed the websites table based on .Databases/website.sql data.
     */
    public function run(): void
    {
        $websites = [
            [
                'id' => 2,
                'created_at' => '2026-03-12 03:52:08',
                'updated_at' => '2026-03-12 03:52:08',
                'type' => 'carousel',
                'image_url' => 'images/69b2a8e8bc3d5.jpg',
                'title' => 'Unciano Colleges Antipolo, Inc.',
                'description' => 'Empowering Minds, Building Futures',
            ],
            [
                'id' => 3,
                'created_at' => '2026-03-12 03:53:35',
                'updated_at' => '2026-03-12 03:53:35',
                'type' => 'carousel',
                'image_url' => 'images/69b2a93f29a24.jpg',
                'title' => 'Your Future Start Here',
                'description' => 'Join Our Growing Community of Achievers.',
            ],
            [
                'id' => 4,
                'created_at' => '2026-03-12 03:54:35',
                'updated_at' => '2026-03-12 03:54:35',
                'type' => 'carousel',
                'image_url' => 'images/69b2a97b26ad6.jpg',
                'title' => 'Excellence in Education',
                'description' => 'Quality Learning Since 1985',
            ],
            [
                'id' => 5,
                'created_at' => '2026-03-12 03:56:05',
                'updated_at' => '2026-03-12 03:56:05',
                'type' => 'event',
                'image_url' => 'images/69b2a9d5b9381.jpg',
                'title' => '35th Commencement Exercises',
                'description' => 'Celebrating the achievements of our graduates as they receive their diplomas and embark on new journeys.',
                'event_date' => '2025-11-05',
            ],
            [
                'id' => 6,
                'created_at' => '2026-03-12 03:57:01',
                'updated_at' => '2026-03-12 03:57:01',
                'type' => 'event',
                'image_url' => 'images/69b2aa0d296f5.jpg',
                'title' => 'Capping, White Coat, Pinning, and Candle Lighting Ceremonies 2025',
                'description' => 'A sacred tradition honoring our healthcare students as they transition into clinical practice.',
                'event_date' => '2025-09-04',
            ],
            [
                'id' => 7,
                'created_at' => '2026-03-12 03:57:48',
                'updated_at' => '2026-03-12 03:57:48',
                'type' => 'event',
                'image_url' => 'images/69b2aa3c0e923.jpg',
                'title' => 'Uncianian\'s Sports Event 2024',
                'description' => 'Annual intramurals featuring basketball, volleyball, and other sports competitions among departments.',
                'event_date' => '2024-12-19',
            ],
            [
                'id' => 8,
                'created_at' => '2026-03-12 03:58:15',
                'updated_at' => '2026-03-12 03:58:15',
                'type' => 'mission',
                'title' => 'Our Mission',
                'description' => 'To provide quality and affordable education that develops competent, morally upright, and socially responsible individuals who are equipped with the knowledge, skills, and values necessary for lifelong learning and global competitiveness.',
            ],
            [
                'id' => 9,
                'created_at' => '2026-03-12 03:58:34',
                'updated_at' => '2026-03-12 03:58:34',
                'type' => 'vision',
                'title' => 'Our Vision',
                'description' => 'To be a leading institution of higher learning in the region, recognized for academic excellence, innovative research, and community service, producing graduates who are catalysts of positive change in society.',
            ],
            [
                'id' => 10,
                'created_at' => '2026-03-12 03:58:56',
                'updated_at' => '2026-03-12 03:58:56',
                'type' => 'goal',
                'title' => 'Leadership',
                'description' => 'Inspiring others to achieve shared goals and make a positive impact.',
            ],
            [
                'id' => 11,
                'created_at' => '2026-03-12 03:59:11',
                'updated_at' => '2026-03-12 03:59:11',
                'type' => 'goal',
                'title' => 'Critical Thinking',
                'description' => 'Analyzing situations and making informed, logical decisions.',
            ],
            [
                'id' => 12,
                'created_at' => '2026-03-12 03:59:23',
                'updated_at' => '2026-03-12 03:59:23',
                'type' => 'goal',
                'title' => 'Innovation',
                'description' => 'Creating new solutions and embracing technological advancement.',
            ],
            [
                'id' => 13,
                'created_at' => '2026-03-12 03:59:36',
                'updated_at' => '2026-03-12 03:59:36',
                'type' => 'goal',
                'title' => 'Integrity',
                'description' => 'Upholding honesty, ethics, and moral values in all endeavors.',
            ],
            [
                'id' => 14,
                'created_at' => '2026-03-12 04:02:48',
                'updated_at' => '2026-03-12 04:02:48',
                'type' => 'program',
                'image_url' => 'images/69b2ab68b5501.jpg',
                'title' => 'Bachelor of Science in Nursing',
                'description' => 'Prepare for a rewarding healthcare career with our comprehensive nursing program featuring clinical rotations, hands-on patient care, and board exam preparation.',
            ],
            [
                'id' => 15,
                'created_at' => '2026-03-12 04:03:21',
                'updated_at' => '2026-03-12 04:03:21',
                'type' => 'program',
                'image_url' => 'images/69b2ab893e667.webp',
                'title' => 'Bachelor of Science in Physical Therapy',
                'description' => 'Master rehabilitation sciences and help patients recover mobility through evidence-based therapeutic interventions and clinical practice.',
            ],
            [
                'id' => 16,
                'created_at' => '2026-03-12 04:03:56',
                'updated_at' => '2026-03-12 04:03:56',
                'type' => 'program',
                'image_url' => 'images/69b2abac80a1a.webp',
                'title' => 'Bachelor in Science in Medical Technology',
                'description' => 'Become a clinical laboratory scientist skilled in diagnostic testing, laboratory procedures, and medical analysis to support healthcare delivery.',
            ],
            [
                'id' => 19,
                'created_at' => '2026-03-12 04:30:18',
                'updated_at' => '2026-03-12 04:30:18',
                'type' => 'contact',
                'contact' => '286970174',
            ],
            [
                'id' => 20,
                'created_at' => '2026-03-12 04:30:40',
                'updated_at' => '2026-03-12 04:30:40',
                'type' => 'contact',
                'contact' => '8697-0174',
            ],
            [
                'id' => 21,
                'created_at' => '2026-03-12 04:30:56',
                'updated_at' => '2026-03-12 04:30:56',
                'type' => 'email',
                'email' => 'ucai.onlineenrollment@gmail.com',
            ],
            [
                'id' => 22,
                'created_at' => '2026-03-12 04:31:08',
                'updated_at' => '2026-03-12 04:31:08',
                'type' => 'email',
                'email' => 'ucai.onlinepayment@gmail.com',
            ],
            [
                'id' => 23,
                'created_at' => '2026-03-12 04:31:41',
                'updated_at' => '2026-03-12 04:31:41',
                'type' => 'social_link',
                'social_link' => 'https://facebook.com/UncianoCollegesInc',
            ],
            [
                'id' => 24,
                'created_at' => '2026-03-12 04:32:50',
                'updated_at' => '2026-03-12 04:32:50',
                'type' => 'office_hour',
                'days' => 'Monday - Friday',
                'is_open' => true,
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
            ],
            [
                'id' => 25,
                'created_at' => '2026-03-12 04:33:11',
                'updated_at' => '2026-03-12 04:33:11',
                'type' => 'office_hour',
                'days' => 'Saturday',
                'is_open' => true,
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
            ],
            [
                'id' => 27,
                'created_at' => '2026-03-12 04:43:40',
                'updated_at' => '2026-03-12 04:45:19',
                'type' => 'location',
                'location' => '75 L. Sumulong Memorial Circle, Antipolo, 1870 Rizal',
                'embedded_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3440.055328403227!2d121.16969092481187!3d14.580166468029896!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c0ab26f059cd%3A0x641cf1649079f713!2sUnciano%20Colleges%20Inc.!5e0!3m2!1sen!2sph!4v1773319376921!5m2!1sen!2sph',
            ],
            [
                'id' => 29,
                'created_at' => '2026-05-03 17:02:35',
                'updated_at' => '2026-05-03 17:02:35',
                'type' => 'office_hour',
                'days' => 'Sunday',
                'is_open' => false,
            ],
        ];

        foreach ($websites as $data) {
            Website::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
        }
    }
}
