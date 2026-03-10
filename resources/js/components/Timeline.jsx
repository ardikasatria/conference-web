import React from "react";

const Timeline = () => {
  const events = [
    { date: "Jan 15, 2024", title: "Submission Opening", description: "Call for Paper Opens" },
    { date: "Mar 31, 2024", title: "Submission Deadline", description: "Final Submission Date" },
    { date: "May 15, 2024", title: "Notification Results", description: "Acceptance Notification" },
    { date: "Sep 26-27, 2024", title: "Conference Dates", description: "Main Event" }
  ];

  return (
    <div className="py-20 px-4">
      <h2 className="text-4xl font-bold text-white text-center mb-16 font-spaceGrotesk">Conference Timeline</h2>
      <div className="max-w-4xl mx-auto">
        <div className="space-y-8">
          {events.map((event, index) => (
            <div key={index} className="flex gap-4">
              <div className="flex flex-col items-center">
                <div className="h-4 w-4 bg-colorGreen rounded-full mt-2"></div>
                {index !== events.length - 1 && <div className="w-1 h-24 bg-gradient-to-b from-colorGreen to-transparent"></div>}
              </div>
              <div className="pb-8">
                <p className="text-colorGreen font-bold text-sm">{event.date}</p>
                <h3 className="text-white text-xl font-bold mt-1">{event.title}</h3>
                <p className="text-gray-400 mt-1">{event.description}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default Timeline;
