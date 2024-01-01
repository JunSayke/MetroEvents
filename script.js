$(document).ready(function () {
	$("body").on("click", ".action-btn", function () {
		const clickedButton = $(this)
		const userId = clickedButton.data("userid")
		const eventId = clickedButton.data("event-id")
		const reviewId = clickedButton.data("review-id")
		const id = clickedButton.attr("id")
		switch (id) {
			case "join-event-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "join_event_request",
						eventId: eventId,
					},
					success: (data) => {
						if (data.success) {
							$(this).attr("id", "cancel-join-event-btn")
							$(this)
								.removeClass("btn-outline-success")
								.addClass("btn-outline-danger")
							$(this).text("Cancel Request")
						}
					},
				})
				break
			case "cancel-join-event-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "cancel_join_event_request",
						eventId: eventId,
					},
					success: (data) => {
						if (data.success) {
							$(this).attr("id", "join-event-btn")
							$(this)
								.removeClass("btn-outline-danger")
								.addClass("btn-outline-success")
							$(this).text("Join")
						}
					},
				})
				break
			case "cancel-event-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "cancel_event",
						eventId: eventId,
						reason: "Test",
					},
					success: (data) => {
						if (data.success) {
							location.reload()
						}
					},
				})
				break
			case "upvote-event-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "upvote_event",
						eventId: eventId,
					},
					success: (data) => {
						if (data.success) {
							const currentUpvoteCount = parseInt($(this).find(".badge").text())
							$(this).attr("id", "remove-upvote-btn")
							$(this).removeClass("btn-outline-primary").addClass("btn-primary")
							$(this)
								.find(".badge")
								.text(currentUpvoteCount + 1)
						}
					},
				})
				break
			case "remove-upvote-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "remove_upvote",
						eventId: eventId,
					},
					success: (data) => {
						if (data.success) {
							const currentUpvoteCount = parseInt($(this).find(".badge").text())
							$(this).attr("id", "upvote-event-btn")
							$(this).removeClass("btn-primary").addClass("btn-outline-primary")
							$(this)
								.find(".badge")
								.text(currentUpvoteCount - 1)
						}
					},
				})
				break
			case "organizer-request-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "organizer_request",
					},
					success: (data) => {
						if (data.success) {
							$(this).attr("id", "cancel-organizer-request-btn")
							$(this)
								.removeClass("btn-outline-success")
								.addClass("btn-outline-danger")
							$(this).text("Cancel Request")
						}
					},
				})
				break
			case "cancel-organizer-request-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "cancel_organizer_request",
					},
					success: (data) => {
						if (data.success) {
							$(this).attr("id", "organizer-request-btn")
							$(this)
								.removeClass("btn-outline-danger")
								.addClass("btn-outline-success")
							$(this).text("Become Organizer")
						}
					},
				})
				break
			case "accept-organizer-request-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "accept_organizer_request",
						userId: userId,
					},
					success: (data) => {
						if (data.success) {
							$(this).closest("tr").remove()
						}
					},
				})
				break
			case "reject-organizer-request-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "reject_organizer_request",
						userId: userId,
					},
					success: (data) => {
						if (data.success) {
							$(this).closest("tr").remove()
						}
					},
				})
				break
			case "delete-review-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "delete_review",
						reviewId: reviewId,
					},
					success: (data) => {
						if (data.success) {
							$(this).parent().remove()
						}
					},
				})
				break
		}
	})
})
