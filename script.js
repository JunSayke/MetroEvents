$(document).ready(function () {
	$.ajaxSetup({
		error: (jqXHR, textStatus, errorThrown) => {
			console.log(`${jqXHR} ${textStatus} ${errorThrown}`)
		},
	})

	$("body").on("click", ".action-btn", function () {
		const clickedButton = $(this)
		const userId = clickedButton.data("userid")
		const eventId = clickedButton.data("event-id")
		const reviewId = clickedButton.data("review-id")
		const notifId = clickedButton.data("notif-id")
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
			case "leave-event-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "leave_event",
						eventId: eventId,
					},
					success: (data) => {
						if (data.success) {
							location.reload()
						}
					},
				})
				break
			case "cancel-event-btn":
				$("#cancel-event-modal").modal("show")
				$("#cancel-event-form").submit(function (event) {
					event.preventDefault()
					const reason = $("#reason").val()
					$.ajax({
						url: "api.php",
						method: "POST",
						data: {
							action: "cancel_event",
							eventId: eventId,
							reason: reason,
						},
						success: (data) => {
							if (data.success) {
								location.reload()
							}
						},
					})
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
							location.reload()
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
							location.reload()
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
							location.reload()
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
							location.reload()
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
			case "accept-join-request-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "accept_join_request",
						userId: userId,
						eventId: eventId,
					},
					success: (data) => {
						if (data.success) {
							$(this).closest("tr").remove()
						}
					},
				})
				break
			case "reject-join-request-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "reject_join_request",
						userId: userId,
						eventId: eventId,
					},
					success: (data) => {
						if (data.success) {
							$(this).closest("tr").remove()
						}
					},
				})
				break
			case "demote-user-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "demote_user",
						userId: userId,
					},
					success: (data) => {
						if (data.success) {
							location.reload()
						}
					},
				})
				break
			case "promote-user-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "promote_user",
						userId: userId,
					},
					success: (data) => {
						if (data.success) {
							location.reload()
						}
					},
				})
				break
			case "delete-user-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "delete_user",
						userId: userId,
					},
					success: (data) => {
						if (data.success) {
							location.reload()
						}
					},
				})
				break
			case "delete-account-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "delete_account",
					},
					success: (data) => {
						if (data.success) {
							window.location.replace("index.php")
						}
					},
				})
				break
			case "delete-notif-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "delete_notif",
						notifId: notifId,
					},
					success: (data) => {
						if (data.success) {
							location.reload()
						}
					},
				})
				break
			case "kick-participant-btn":
				$.ajax({
					url: "api.php",
					method: "POST",
					data: {
						action: "remove_participant",
						userId: userId,
						eventId: eventId,
					},
					success: (data) => {
						console.log(data)
						if (data.success) {
							location.reload()
						}
					},
				})
				break
		}
	})
})
