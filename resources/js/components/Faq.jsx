import React, { useState } from "react";
import { ChevronDown } from 'lucide-react';

const Faq = () => {
  const [activeIndex, setActiveIndex] = useState(null);

  const faqData = [
    {
      question: "What is the conference theme?",
      answer: "The conference focuses on Sustainability of Sciences for the Future, covering various fields including energy, environment, biomedicine, and technology."
    },
    {
      question: "What are the submission deadlines?",
      answer: "The abstract submission deadline is March 31, 2024. Authors will be notified of acceptance status by May 15, 2024."
    },
    {
      question: "How do I register?",
      answer: "You can register online through our website. Choose your preferred package and complete the registration form with your details."
    },
    {
      question: "What is included in my registration?",
      answer: "Depending on your package, registration includes conference access, certificate of participation, and potential publication opportunities in accredited journals."
    },
    {
      question: "Can I present virtually?",
      answer: "Yes, we offer both in-person and virtual presentation options. Please indicate your preference during registration."
    },
    {
      question: "Is there accommodation available?",
      answer: "We have negotiated discounted rates at nearby hotels. Information and booking details will be provided to registered participants."
    }
  ];

  return (
    <div className="py-20 px-4">
      <h2 className="text-4xl font-bold text-white text-center mb-4 font-spaceGrotesk">Frequently Asked Questions</h2>
      <p className="text-center text-gray-400 mb-16">Find answers to common questions about the conference</p>
      <div className="max-w-3xl mx-auto space-y-4">
        {faqData.map((item, index) => (
          <div key={index} className="border border-gray-700 rounded-lg overflow-hidden">
            <button
              className="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-900 transition"
              onClick={() => setActiveIndex(activeIndex === index ? null : index)}
            >
              <span className="text-white font-semibold">{item.question}</span>
              <ChevronDown className={`text-colorGreen transition transform ${activeIndex === index ? 'rotate-180' : ''}`} size={20} />
            </button>
            {activeIndex === index && (
              <div className="px-6 py-4 bg-gray-900 border-t border-gray-700">
                <p className="text-gray-400">{item.answer}</p>
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  );
};

export default Faq;
