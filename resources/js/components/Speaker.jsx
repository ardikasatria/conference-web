import React from "react";

const Speaker = () => {
  return (
    <div className="py-20 px-4">
      <h2 className="text-4xl font-bold text-white text-center mb-16 font-spaceGrotesk">Distinguished Speakers</h2>
      <div className="max-w-4xl mx-auto">
        <p className="text-gray-400 text-center text-lg">
          Featuring renowned researchers and experts from around the world who will share their latest insights and findings in their respective fields.
        </p>
        <div className="mt-12 text-center">
          <p className="text-colorGreen font-bold">More speakers to be announced soon!</p>
        </div>
      </div>
    </div>
  );
};

export default Speaker;
