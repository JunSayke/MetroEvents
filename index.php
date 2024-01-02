<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $.ajax({
            url: "api.php",
            method: "POST",
            data: {
                action: "get_upcoming_events",
            },
            success: (data) => {
                let delay = 1000
                if (data.success) {
                    for (key in data.events) {
                        const title = data.events[key].title
                        setTimeout(() => {
                            const toastElement = $('<div class="toast align-items-center text-white bg-primary border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">')
                            toastElement.append($('<div class="toast-header">').append(`<strong class="me-auto toast-title">Upcoming Event!</strong>`).append('<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>'))
                            toastElement.append($('<div class="toast-body">').text(`Checkout "${title}" event in the events page.`))
                            $("#toast-container").append(toastElement)
                            const toast = new bootstrap.Toast(toastElement)
                            toast.show()
                        }, delay)
                        delay += 1000
                    }
                }
            },
        })
    </script>
    <script src="script.js"></script>
    <title>Home - Metro Events</title>
</head>

<body>
    <?php include("header.php"); ?>
    <section class="text-center bg-light py-5">
        <div class="container">
            <h1 class="display-4">Welcome to Metro Events</h1>
            <p class="lead">To be honest, I don't feel like making a website for now.</p>
            <a class="btn btn-primary btn-lg" href="learn_more.php" role="button">Learn More</a>
        </div>
    </section>
    <?php include("footer.php") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</html>