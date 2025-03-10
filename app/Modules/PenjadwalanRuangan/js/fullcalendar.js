import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import resourceTimelinePlugin from "@fullcalendar/resource-timeline";

document.addEventListener("DOMContentLoaded", function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var calendarEl = document.getElementById("calendar");

    if (calendarEl) {
        var calendar = new Calendar(calendarEl, {
            plugins: [
                dayGridPlugin,
                timeGridPlugin,
                interactionPlugin,
                resourceTimelinePlugin,
            ],
            schedulerLicenseKey: "GPL-My-Project-Is-Open-Source",
            initialView: "resourceTimelineDay",
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "",
            },
            droppable: true,
            resourceAreaHeaderContent: "Ruangan",
            resources: "/api/v1/ruangan/nama", // Ensure resources are loaded here
            events: {
                url: "/api/v1/penjadwalan/event", // Events URL
                method: "GET",
                failure: function () {
                    alert("Failed to get events");
                },
            },
            selectable: true,
            timezone: "UTC",
            eventResize: function (info) {
                // Ambil data yang sudah diubah
                var updatedStart = info.event.startStr;
                var updatedEnd = info.event.endStr;

                $.ajax({
                    url: "/api/services/v1/schedule/action", // URL untuk mengirim data
                    method: "POST", // Metode HTTP
                    contentType: "application/json", // Mengirim data dalam format JSON
                    data: JSON.stringify({
                        id: info.event.id,
                        title: info.event.title,
                        start: updatedStart, // Waktu mulai yang diperbarui
                        end: updatedEnd, // Waktu akhir yang diperbarui
                        type: "update", // Jenis aksi (update)
                    }),
                    success: function (response) {
                        calendar.refetchEvents(); // Memperbarui event setelah perubahan
                        alert("Event duration updated successfully");
                    },
                    error: function (xhr, status, error) {
                        console.error("Error updating event:", error);
                        alert("Error updating event");
                    },
                });
            },
            select: function (info) {
                const validSessions = [
                    { start: "07:00", end: "09:00" }, // Sesi 1
                    { start: "09:00", end: "11:00" }, // Sesi 2
                    { start: "13:00", end: "15:00" }, // Sesi 3
                    { start: "15:00", end: "17:00" }, // Sesi 4
                ];

                const selectedStartTime = new Date(
                    info.start
                ).toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                    hour12: false, // 24-hour format
                });

                const selectedEndTime = new Date(info.end).toLocaleTimeString(
                    [],
                    {
                        hour: "2-digit",
                        minute: "2-digit",
                        hour12: false,
                    }
                );

                console.log(selectedEndTime, selectedStartTime);
                // Check if the selected time is within valid sessions
                let isValid = false;
                validSessions.forEach(function (session) {
                    // Convert both session and selected times into comparable strings (HH:mm)
                    if (
                        selectedStartTime >= session.start &&
                        selectedEndTime <= session.end
                    ) {
                        isValid = true;
                    }
                });

                // If the time is invalid, show an alert and prevent showing the modal
                if (!isValid) {
                    alert(
                        "The selected time is outside of valid session hours. Valid hours: 07:00-09:00, 09:00-11:00, 13:00-15:00, 15:00-17:00."
                    );
                    return; // Prevent the modal from showing
                }

                // If valid time, show the modal
                $("#eventModal").modal("show");

                // Handle the event saving logic when the "Save" button is clicked
                $("#saveEventBtn").on("click", function () {
                    var eventType = $("#eventSelect").val(); // Get event type from dropdown

                    if (eventType) {
                        $.ajax({
                            url: "/api/services/v1/schedule/action",
                            type: "POST",
                            data: {
                                start: new Date(info.start).toISOString(),
                                end: new Date(info.end).toISOString(),
                                id_ruangan: info.resource.id,
                                type: "add",
                                title: eventType,
                            },
                            success: function (response) {
                                console.log(response);
                                calendar.refetchEvents();
                                alert("Event successfully added");
                                $("#eventModal").modal("hide");
                            },
                            error: function (xhr, status, error) {
                                console.error("Error:", error);
                                alert("Error adding event");
                                $("#eventModal").modal("hide");
                            },
                        });
                    } else {
                        alert("Please enter an event name.");
                    }
                });
            },
            editable: true,
            eventDrop: function (info) {
                let resources = info.event.getResources();

                $.ajax({
                    url: "/api/services/v1/schedule/action", // URL untuk mengirim data
                    method: "POST", // Metode HTTP
                    contentType: "application/json", // Mengirim data dalam format JSON
                    data: JSON.stringify({
                        id: info.event.id,
                        title: info.event.title,
                        start: info.event.startStr,
                        end: info.event.endStr,
                        resourceId: resources[0]._resource.id, // Mengambil resourceId
                        type: "update", // Jenis aksi (update)
                    }),
                    success: function (response) {
                        calendar.refetchEvents(); // Memperbarui event setelah pemindahan
                        alert("Event updated successfully");
                    },
                    error: function (xhr, status, error) {
                        console.error("Error updating event:", error);
                        alert("Error updating event");
                    },
                });
            },
            eventClick: function (info) {
                if (confirm("Are you sure you want to delete this event?")) {
                    $.ajax({
                        url: "/api/services/v1/schedule/action", // URL untuk mengirim data
                        method: "POST", // Metode HTTP
                        contentType: "application/json", // Mengirim data dalam format JSON
                        data: JSON.stringify({
                            id: info.event.id,
                            type: "delete", // Jenis aksi (hapus)
                        }),
                        success: function (response) {
                            calendar.refetchEvents(); // Memperbarui event setelah penghapusan
                            alert("Event deleted successfully");
                        },
                        error: function (xhr, status, error) {
                            console.error("Error deleting event:", error);
                            alert("Error deleting event");
                        },
                    });
                }
            },
        });

        calendar.render();

        document
            .getElementById("calendarDate")
            .addEventListener("change", function (e) {
                var selectedDate = e.target.value;
                if (selectedDate) {
                    calendar.gotoDate(selectedDate); // Mengatur kalender ke tanggal yang dipilih
                }
            });
    }
});
