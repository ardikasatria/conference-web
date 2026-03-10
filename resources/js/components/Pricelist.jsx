import React from "react";
import { Check } from 'lucide-react';

const Pricelist = () => {
  const packages = [
    {
      name: "Student",
      price: "$50",
      description: "For students presenting research",
      features: ["Conference access", "One paper submission", "Certificate"]
    },
    {
      name: "Professional",
      price: "$150",
      description: "For researchers and professionals",
      features: ["Conference access", "Two paper submissions", "Networking events", "Certificate", "Proceeding publication"],
      popular: true
    },
    {
      name: "Group",
      price: "$250",
      description: "For groups (3+ participants)",
      features: ["Bulk discount", "Multiple papers", "VIP seating", "Networking sessions", "Certificates", "Priority publication"]
    }
  ];

  return (
    <div className="py-20 px-4">
      <h2 className="text-4xl font-bold text-white text-center mb-4 font-spaceGrotesk">Registration Packages</h2>
      <p className="text-center text-gray-400 mb-16">Choose the perfect package for your needs</p>
      <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        {packages.map((pkg, index) => (
          <div key={index} className={`rounded-lg p-8 transition transform hover:scale-105 ${pkg.popular ? 'bg-gradient-to-br from-colorGreen to-colorGreen/30 border-2 border-colorGreen' : 'bg-gray-900 border border-gray-700'}`}>
            {pkg.popular && <span className="inline-block bg-colorGreen text-black px-3 py-1 rounded-full text-sm font-bold mb-4">Most Popular</span>}
            <h3 className={`text-2xl font-bold mb-2 ${pkg.popular ? 'text-black' : 'text-white'}`}>{pkg.name}</h3>
            <p className={`text-sm mb-4 ${pkg.popular ? 'text-black/70' : 'text-gray-400'}`}>{pkg.description}</p>
            <div className="text-4xl font-bold text-colorGreen mb-6">{pkg.price}</div>
            <ul className="space-y-3 mb-8">
              {pkg.features.map((feature, i) => (
                <li key={i} className={`flex items-center ${pkg.popular ? 'text-black' : 'text-gray-300'}`}>
                  <Check size={18} className="mr-2 text-colorGreen" /> {feature}
                </li>
              ))}
            </ul>
            <button className={`w-full py-2 px-4 rounded font-bold transition ${pkg.popular ? 'bg-black text-colorGreen hover:bg-gray-800' : 'bg-colorGreen text-black hover:bg-white'}`}>
              Register Now
            </button>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Pricelist;
