import React from 'react';

const TopicsFaq = () => {
  const topics = [
    'Computer Science & Information Technology',
    'Artificial Intelligence & Machine Learning',
    'Data Science & Big Data Analytics',
    'Cybersecurity & Network Security',
    'Software Engineering & Development',
    'Internet of Things (IoT)',
    'Biotechnology & Biomedical Engineering',
    'Environmental Science & Sustainability',
    'Renewable Energy & Green Technology',
    'Materials Science & Nanotechnology',
    'Chemistry & Molecular Sciences',
    'Physics & Applied Sciences'
  ];

  return (
    <div className="container mx-auto px-4 py-16">
      <div className="text-center mb-12">
        <h2 className="text-3xl md:text-4xl font-bold text-white mb-4 font-spaceGrotesk">
          Conference Topics
        </h2>
        <hr className="w-24 mx-auto border-2 border-colorGreen mb-8" />
        <p className="text-white text-lg">
          We welcome submissions in the following areas (but not limited to):
        </p>
      </div>
      
      <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
        {topics.map((topic, index) => (
          <div 
            key={index}
            className="bg-white/10 backdrop-blur-sm rounded-lg p-6 hover:bg-white/20 transition duration-300"
          >
            <div className="flex items-start space-x-3">
              <div className="flex-shrink-0">
                <div className="w-2 h-2 bg-colorGreen rounded-full mt-2"></div>
              </div>
              <p className="text-white font-medium">{topic}</p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default TopicsFaq;
