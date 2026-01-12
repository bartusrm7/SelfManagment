/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

document.addEventListener("DOMContentLoaded", async function () {
    const calendarEl = document.getElementById("calendar");
    if (!calendarEl) return;

    const response = await fetch("/meetings/json");
    const data = await response.json();

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        selectable: true,
        events: data.data,
        select: function () {
            document.getElementById("meetingModal").style.display = "flex";
        },
        eventClick: function (info) {
            document.getElementById("meetingChosenModal").style.display =
                "flex";
            document.getElementById("meetingChosenName").value =
                info.event.title;
            document.getElementById("meetingChosenId").value = info.event.id;
        },
    });
    calendar.render();

    document
        .getElementById("saveMeeting")
        .addEventListener("click", async () => {
            const meetingName = document.getElementById("meetingName").value;
            const meetingDescription =
                document.getElementById("meetingDescription").value;
            const meetingStartDate =
                document.getElementById("meetingStartDate").value;
            const meetingEndDate =
                document.getElementById("meetingEndDate").value;

            const response = await fetch("/meetings", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    title: meetingName,
                    description: meetingDescription,
                    start: meetingStartDate,
                    end: meetingEndDate,
                }),
            });
            const data = await response.json();
            calendar.addEvent(data);

            document.getElementById("meetingModal").style.display = "none";
            document.getElementById("meetingName").value = "";
            document.getElementById("meetingDescription").value = "";
            document.getElementById("meetingStartDate").value = "";
            document.getElementById("meetingEndDate").value = "";
        });

    document
        .getElementById("removeMeetingBtn")
        .addEventListener("click", async () => {
            const id = document.getElementById("meetingChosenId").value;

            await fetch(`/meetings/remove-meeting/${id}`, {
                method: "POST",
            });
            calendar.getEventById(id).remove();
            document.getElementById("meetingChosenModal").style.display =
                "none";
        });
});

import "./styles/app.scss";
import "bootstrap-icons/font/bootstrap-icons.css";

import "@coreui/coreui/dist/js/coreui.bundle.min.js";
import "@coreui/coreui/dist/css/coreui.min.css";
