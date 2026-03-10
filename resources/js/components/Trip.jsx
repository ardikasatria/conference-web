import React from "react";

const Trip = () => {
  return (
    <div className="py-20 px-4">
      <h2 className="text-4xl font-bold text-white text-center mb-16 font-spaceGrotesk">Getting to the Conference</h2>
      <div className="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
        <div className="bg-gradient-to-br from-colorGreen/10 to-transparent border border-colorGreen/30 p-8 rounded-lg">
          <h3 className="text-white font-bold text-xl mb-4">✈️ By Air</h3>
          <p className="text-gray-400">Fly into Radin Intan II Airport (TKG) in Lampung. The airport is approximately 30 km from ITERA campus with convenient ground transportation available.</p>
        </div>
        <div className="bg-gradient-to-br from-colorGreen/10 to-transparent border border-colorGreen/30 p-8 rounded-lg">
          <h3 className="text-white font-bold text-xl mb-4">🚌 By Bus</h3>
          <p className="text-gray-400">Multiple bus services operate between major cities and Lampung. Regular bus terminals are available with connections to the conference venue.</p>
        </div>
        <div className="bg-gradient-to-br from-colorGreen/10 to-transparent border border-colorGreen/30 p-8 rounded-lg">
          <h3 className="text-white font-bold text-xl mb-4">🚗 By Car</h3>
          <p className="text-gray-400">ITERA is easily accessible by car with adequate parking facilities. GPS coordinates and detailed directions will be provided to registered participants.</p>
        </div>
      </div>
    </div>
  );
};

export default Trip;
