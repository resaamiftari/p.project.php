<?php
// Generate book cover images
$books_images = [
    'the_great_gatsby.jpg' => ['title' => 'The Great Gatsby', 'color' => '#1a472a'],
    'to_kill_a_mockingbird.jpg' => ['title' => 'To Kill a Mockingbird', 'color' => '#2c3e50'],
    '1984.jpg' => ['title' => '1984', 'color' => '#8b0000'],
    'pride_and_prejudice.jpg' => ['title' => 'Pride & Prejudice', 'color' => '#d4a574'],
    'the_catcher_in_the_rye.jpg' => ['title' => 'The Catcher in the Rye', 'color' => '#c41e3a'],
    'jane_eyre.jpg' => ['title' => 'Jane Eyre', 'color' => '#4a235a'],
    'wuthering_heights.jpg' => ['title' => 'Wuthering Heights', 'color' => '#2d3436'],
    'the_hobbit.jpg' => ['title' => 'The Hobbit', 'color' => '#8b4513'],
    'lord_of_the_rings.jpg' => ['title' => 'Lord of the Rings', 'color' => '#2d5016'],
    'harry_potter_1.jpg' => ['title' => 'Harry Potter 1', 'color' => '#740001'],
    'harry_potter_2.jpg' => ['title' => 'Harry Potter 2', 'color' => '#740001'],
    'harry_potter_3.jpg' => ['title' => 'Harry Potter 3', 'color' => '#740001'],
    'narnia.jpg' => ['title' => 'Chronicles of Narnia', 'color' => '#8b6914'],
    'moby_dick.jpg' => ['title' => 'Moby Dick', 'color' => '#34495e'],
    'treasure_island.jpg' => ['title' => 'Treasure Island', 'color' => '#8b4513'],
    'sherlock_holmes.jpg' => ['title' => 'Sherlock Holmes', 'color' => '#2c3e50'],
    'dorian_gray.jpg' => ['title' => 'Dorian Gray', 'color' => '#4a235a'],
    'frankenstein.jpg' => ['title' => 'Frankenstein', 'color' => '#2d3436'],
    'dracula.jpg' => ['title' => 'Dracula', 'color' => '#8b0000'],
    'invisible_man.jpg' => ['title' => 'The Invisible Man', 'color' => '#34495e'],
    'time_machine.jpg' => ['title' => 'The Time Machine', 'color' => '#2c3e50'],
    'tale_of_two_cities.jpg' => ['title' => 'A Tale of Two Cities', 'color' => '#8b0000'],
    'oliver_twist.jpg' => ['title' => 'Oliver Twist', 'color' => '#2c3e50'],
    'great_expectations.jpg' => ['title' => 'Great Expectations', 'color' => '#34495e'],
    'the_odyssey.jpg' => ['title' => 'The Odyssey', 'color' => '#2d5016'],
    'the_iliad.jpg' => ['title' => 'The Iliad', 'color' => '#8b4513'],
    'dune.jpg' => ['title' => 'Dune', 'color' => '#8b6914'],
    'foundation.jpg' => ['title' => 'Foundation', 'color' => '#2c3e50'],
    'brave_new_world.jpg' => ['title' => 'Brave New World', 'color' => '#34495e'],
    'fahrenheit_451.jpg' => ['title' => 'Fahrenheit 451', 'color' => '#8b0000'],
    'handmaids_tale.jpg' => ['title' => 'The Handmaid\'s Tale', 'color' => '#8b0000'],
    'animal_farm.jpg' => ['title' => 'Animal Farm', 'color' => '#2c3e50'],
    'slaughterhouse_five.jpg' => ['title' => 'Slaughterhouse Five', 'color' => '#2d3436'],
    'odyssey_homer.jpg' => ['title' => 'Odyssey', 'color' => '#8b4513'],
    'sense_sensibility.jpg' => ['title' => 'Sense and Sensibility', 'color' => '#d4a574'],
    'emma.jpg' => ['title' => 'Emma', 'color' => '#d4a574'],
    'scarlet_letter.jpg' => ['title' => 'The Scarlet Letter', 'color' => '#8b0000'],
    'huckleberry_finn.jpg' => ['title' => 'Huckleberry Finn', 'color' => '#8b6914'],
    'alice_wonderland.jpg' => ['title' => 'Alice in Wonderland', 'color' => '#4a235a'],
    'jungle_book.jpg' => ['title' => 'The Jungle Book', 'color' => '#2d5016'],
];

$image_dir = __DIR__ . '/assets/images/books/';

if (!is_dir($image_dir)) {
    mkdir($image_dir, 0755, true);
}

// Create placeholder images for each book
foreach ($books_images as $filename => $data) {
    $filepath = $image_dir . $filename;
    
    // Skip if file already exists
    if (file_exists($filepath)) {
        continue;
    }
    
    // Create image
    $image = imagecreatetruecolor(250, 380);
    
    // Parse hex color
    $color_hex = $data['color'];
    $r = hexdec(substr($color_hex, 1, 2));
    $g = hexdec(substr($color_hex, 3, 2));
    $b = hexdec(substr($color_hex, 5, 2));
    $fill_color = imagecolorallocate($image, $r, $g, $b);
    $white = imagecolorallocate($image, 255, 255, 255);
    $gold = imagecolorallocate($image, 218, 165, 32);
    
    // Fill background
    imagefilledrectangle($image, 0, 0, 250, 380, $fill_color);
    
    // Add decorative border
    imagerectangle($image, 5, 5, 245, 375, $gold);
    imagerectangle($image, 10, 10, 240, 370, $white);
    
    // Add title text (wordwrap)
    $title = $data['title'];
    $font_size = 3;
    
    // Calculate text position
    $text_box = imagettfbbox($font_size, 0, __DIR__ . '/assets/fonts/arial.ttf', $title);
    $text_width = $text_box[2] - $text_box[0];
    $x = (250 - $text_width) / 2;
    $y = 190;
    
    // Add book icon placeholder
    imagefilledrectangle($image, 80, 60, 170, 130, $gold);
    imagestring($image, 5, 110, 90, 'BOOK', $fill_color);
    
    // Add title in lower half
    $lines = explode(' ', $title);
    $y_offset = 200;
    foreach (array_chunk($lines, 3) as $chunk) {
        $line = implode(' ', $chunk);
        imagestring($image, 2, 20, $y_offset, $line, $white);
        $y_offset += 25;
    }
    
    // Save image
    imagejpeg($image, $filepath, 85);
    imagedestroy($image);
}

echo "Book cover images generated successfully!";
?>
