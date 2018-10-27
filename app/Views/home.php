<!doctype html>
<head>
    <meta charset="utf-8">
    <title>EST test</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>

<body>
<main role="main" class="container">
    <form id="form-work" class="row">
        <div class="form-group col-sm-4">
            <label>Work name</label>
            <input type="name" class="form-control" id="name" placeholder="Enter work name" required>
        </div>
        <div class="form-group col-sm-4">
            <label>Starting Date - Ending Date</label>
            <input class="form-control" id="date-picker">
        </div>
        <div class="form-group col-sm-2">
            <label>Status</label>
            <select class="custom-select" id="status">
                <option value="1" selected>Planning</option>
                <option value="2">Doing</option>
                <option value="3">Complete</option>
            </select>
        </div>
        <div class="form-group col-sm-2">
            <br/>
            <button id="submit" type="submit" class="btn btn-success">Save</button>
            <button id="cancel" type="button" class="btn btn-dark" style="display: none">Cancel</button>
        </div>
    </form>
    <div id="errors">
    </div>
    <div class="row">
        <h6>Status: <i>Planning is blue color</i>, <i>Doing is Green color</i>, <i>Complete is pink color</i></h6>
    </div>
    <div class="row">
        <div id='calendar'></div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
