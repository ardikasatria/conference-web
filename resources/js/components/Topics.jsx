import React from "react";

const Topics = () => {
  const topics = [
    "Sustainable Energy & Green Technology",
    "Climate Change & Environmental Science",
    "Biomedical & Life Sciences",
    "Materials Science & Nanotechnology",
    "Artificial Intelligence & Robotics",
    "Water Resources & Aquatic Sciences"
  ];

  return (
    <div className="py-20 px-4">
      <h2 className="text-4xl font-bold text-white text-center mb-4 font-spaceGrotesk">Conference Topics</h2>
      <p className="text-center text-gray-400 mb-16 max-w-2xl mx-auto">We welcome submissions covering a wide range of topics in science and technology</p>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
        {topics.map((topic, index) => (
          <div key={index} className="bg-gradient-to-br from-colorGreen/10 to-transparent border border-colorGreen/30 p-6 rounded-lg hover:border-colorGreen transition">
            <h3 className="text-white font-bold text-lg">{topic}</h3>
            <div className="mt-4 h-1 bg-gradient-to-r from-colorGreen to-transparent w-12"></div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Topics;
