import React from "react";

const Keynote = () => {
  const speakers = [
    { name: "Dr. Jane Smith", title: "Distinguished Professor", affiliation: "International Institute" },
    { name: "Prof. John Davis", title: "Research Director", affiliation: "Technology Center" },
    { name: "Dr. Maria Garcia", title: "Lead Scientist", affiliation: "Science Foundation" }
  ];

  return (
    <div className="py-20 px-4">
      <h2 className="text-4xl font-bold text-white text-center mb-16 font-spaceGrotesk">Keynote Speakers</h2>
      <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
        {speakers.map((speaker, index) => (
          <div key={index} className="text-center">
            <div className="w-32 h-32 bg-gradient-to-br from-colorGreen to-gray-800 rounded-full mx-auto mb-4 flex items-center justify-center">
              <span className="text-white text-4xl font-bold">{speaker.name.charAt(0)}</span>
            </div>
            <h3 className="text-white font-bold text-lg">{speaker.name}</h3>
            <p className="text-colorGreen font-semibold">{speaker.title}</p>
            <p className="text-gray-400 text-sm mt-2">{speaker.affiliation}</p>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Keynote;
