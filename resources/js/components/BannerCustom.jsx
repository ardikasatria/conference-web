import React from 'react';

const BannerCustom = ({ name }) => {
  return (
    <div className="relative bg-gradient-to-r from-green-900 to-green-700 py-24 px-4">
      <div className="container mx-auto text-center">
        <h1 className="text-4xl md:text-6xl font-bold text-white font-spaceGrotesk">
          {name}
        </h1>
      </div>
    </div>
  );
};

export default BannerCustom;
