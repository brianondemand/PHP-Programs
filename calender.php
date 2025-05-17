<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Calendar</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
<style>
.calendar-day {
    height: 80px;
}

.current-day {
    background-color: rgba(59, 130, 246, 0.2);
    border: 2px solid #3b82f6;
}
</style>
</head>

<body class="bg-gray-100 font-sans">

<!-- PHP CODE START HERE -->
<?php

// Get current date information
$currentDay = date("j");
$currentMonth = date("n");
$currentYear = date("Y");
$currentMonthName = date("F");
$currentTime = date("h:i:s A");
$currentFullDate = date("l, F j, Y");

// Get the first day of the month

$firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
$numberDays = date("t", $firstDayOfMonth);
$startingDay = date("w", $firstDayOfMonth);

// Days of the week
$days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
?>

<div class="container mx-auto p-4">
<div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto">
<!-- Clock Section -->
<div class="text-center mb-6">
<h2 class="text-2xl font-bold text-gray-800 mb-2">Current Time</h2>
<div class="text-4xl font-bold text-blue-600" id="current-time">
<?php echo $currentTime; ?>
</div>
<div class="text-lg text-gray-600 mt-2" id="current-date">
<?php echo $currentFullDate; ?>
</div>
</div>

<!-- Calendar Section -->
<div class="mt-8">
<h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">
<?php echo $currentMonthName . " " . $currentYear; ?>
</h2>



<?php
include './userName.php';
?>

<div class="grid grid-cols-7 gap-1">
<!-- Days of week headers -->
<?php foreach ($days as $day): ?>
<div class="text-center font-bold py-2 bg-gray-200"><?php echo $day; ?></div>
<?php endforeach; ?>

<!-- Empty cells before start of month -->
<?php for ($i = 0; $i < $startingDay; $i++): ?>
<div class="calendar-day bg-gray-50 p-2"></div>
<?php endfor; ?>

<!-- Days of the month -->
<?php for ($day = 1; $day <= $numberDays; $day++): ?>
<?php $isCurrentDay = ($day == $currentDay); ?>
<?php $cellClass = $isCurrentDay ? 'calendar-day current-day p-2' : 'calendar-day bg-white p-2'; ?>

<div class="<?php echo $cellClass; ?>">
<div class="font-bold text-right"><?php echo $day; ?></div>
</div>
<?php endfor; ?>

<!-- Fill remaining cells to complete the grid -->
<?php
$remainingCells = 7 - (($startingDay + $numberDays) % 7);
if ($remainingCells < 7):
    for ($i = 0; $i < $remainingCells; $i++):
        ?>
        <div class="calendar-day bg-gray-50 p-2"></div>
        <?php
        endfor;
    endif;
?>

<!-- PHP CODE ENDS HERE -->


</div>
</div>
</div>
</div>

<script>
// Function to update the time
function updateTime() {
    const now = new Date();

    // Format hours (12-hour format)
    let hours = now.getHours();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // Convert 0 to 12

    // Format minutes and seconds with leading zeros
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    // Update time display
    document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds} ${ampm}`;
}

// Update time immediately and then every second
updateTime();
setInterval(updateTime, 1000);
</script>
</body>

</html>
