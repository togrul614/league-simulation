<!-- resources/views/standings.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>League Standings</title>
</head>
<body>
<h1>League Standings</h1>
<table>
    <tr>
        <th>Position</th>
        <th>Team</th>
        <th>Points</th>
        <th>Goals Scored</th>
        <th>Goals Conceded</th>
        <th>Goal Difference</th>
    </tr>
    @foreach($teams as $index => $team)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $team->name }}</td>
            <td>{{ $team->points }}</td>
            <td>{{ $team->goalsScored }}</td>
            <td>{{ $team->goalsConceded }}</td>
            <td>{{ $team->goalsScored - $team->goalsConceded }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
