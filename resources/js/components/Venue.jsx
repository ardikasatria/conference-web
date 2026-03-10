import React from "react";
import { MapPin, Navigation } from 'lucide-react';

const Venue = () => {
  return (
    <div className="py-20 px-4">
      <h2 className="text-4xl font-bold text-white text-center mb-16 font-spaceGrotesk">Conference Venue</h2>
      <div className="max-w-4xl mx-auto">
        <div className="bg-gray-900 rounded-lg p-8 mb-8">
          <div className="flex items-center mb-6">
            <MapPin className="text-colorGreen w-8 h-8 mr-4" />
            <div>
              <h3 className="text-white text-2xl font-bold">Lampung, Indonesia</h3>
              <p className="text-gray-400">Institut Teknologi Sumatera (ITERA)</p>
            </div>
          </div>
          <p className="text-gray-400 mb-6">
            The conference will be held at the state-of-the-art facilities of ITERA. The venue provides excellent conference facilities, accommodation options, and is easily accessible from major transportation hubs.
          </p>
          <div className="bg-gray-800 rounded h-64 flex items-center justify-center">
            <p className="text-gray-400">Map will be displayed here</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Venue;
