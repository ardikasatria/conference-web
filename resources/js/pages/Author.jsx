import React from 'react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import BannerCustom from '../components/BannerCustom';

const Author = () => {
  return (
    <>
      <Navbar />
      <BannerCustom name="Author Guidelines" />
      
      <div className="container mx-auto px-4 py-20">
        <div className="text-center mb-16">
          <h1 className="text-4xl md:text-5xl font-bold text-white font-spaceGrotesk mb-4">
            Author Guidelines
          </h1>
          <hr className="w-24 mx-auto border-2 border-colorGreen" />
        </div>

        <div className="max-w-4xl mx-auto text-white space-y-8">
          <div className="bg-white/10 backdrop-blur-sm rounded-lg p-8">
            <h2 className="text-3xl font-bold text-colorGreen mb-4 font-spaceGrotesk">
              Paper Submission
            </h2>
            <p className="text-lg leading-relaxed mb-4">
              Papers must be submitted electronically through the conference submission system. 
              All submissions will be reviewed through a blind peer-review process.
            </p>
            <div className="mt-4">
              <h3 className="text-xl font-bold mb-2">Submission Requirements:</h3>
              <ul className="list-disc list-inside space-y-2 ml-4">
                <li>Papers must be original and not previously published</li>
                <li>Maximum length: 6-8 pages (including references)</li>
                <li>Format: PDF following the conference template</li>
                <li>Language: English</li>
                <li>Font: Times New Roman, 12pt</li>
              </ul>
            </div>
          </div>

          <div className="bg-white/10 backdrop-blur-sm rounded-lg p-8">
            <h2 className="text-3xl font-bold text-colorGreen mb-4 font-spaceGrotesk">
              Paper Format
            </h2>
            <p className="text-lg leading-relaxed mb-4">
              Papers should be formatted according to the conference template and include the following sections:
            </p>
            <ul className="list-disc list-inside space-y-2 ml-4">
              <li><strong>Title:</strong> Concise and descriptive</li>
              <li><strong>Authors:</strong> Full names and affiliations</li>
              <li><strong>Abstract:</strong> 150-250 words summarizing the paper</li>
              <li><strong>Keywords:</strong> 3-6 relevant keywords</li>
              <li><strong>Introduction:</strong> Background and research objectives</li>
              <li><strong>Methodology:</strong> Research methods and approach</li>
              <li><strong>Results and Discussion:</strong> Findings and analysis</li>
              <li><strong>Conclusion:</strong> Summary and future work</li>
              <li><strong>References:</strong> All cited works in IEEE format</li>
            </ul>
          </div>

          <div className="bg-white/10 backdrop-blur-sm rounded-lg p-8">
            <h2 className="text-3xl font-bold text-colorGreen mb-4 font-spaceGrotesk">
              Review Process
            </h2>
            <p className="text-lg leading-relaxed mb-4">
              All submitted papers will undergo a rigorous peer-review process:
            </p>
            <ol className="list-decimal list-inside space-y-2 ml-4">
              <li>Papers are checked for plagiarism and formatting compliance</li>
              <li>Each paper is reviewed by at least 2 reviewers</li>
              <li>Reviews are based on originality, technical quality, and relevance</li>
              <li>Authors receive feedback and notification of acceptance</li>
              <li>Accepted papers require final camera-ready submission</li>
            </ol>
          </div>

          <div className="bg-white/10 backdrop-blur-sm rounded-lg p-8">
            <h2 className="text-3xl font-bold text-colorGreen mb-4 font-spaceGrotesk">
              Publication
            </h2>
            <p className="text-lg leading-relaxed mb-4">
              Accepted and presented papers will be published in the conference proceedings. 
              Selected papers may be invited for extended publication in partner journals.
            </p>
            <div className="mt-4 p-4 bg-colorGreen/20 rounded-lg border-l-4 border-colorGreen">
              <p className="text-sm">
                <strong>Note:</strong> At least one author of each accepted paper must register 
                and present the paper at the conference for the paper to be included in the proceedings.
              </p>
            </div>
          </div>

          <div className="bg-white/10 backdrop-blur-sm rounded-lg p-8">
            <h2 className="text-3xl font-bold text-colorGreen mb-4 font-spaceGrotesk">
              Download Templates
            </h2>
            <div className="space-y-4">
              <button className="w-full bg-colorGreen text-black font-bold py-3 px-6 rounded-lg hover:bg-green-400 transition duration-300">
                Download Paper Template (Word)
              </button>
              <button className="w-full bg-colorGreen text-black font-bold py-3 px-6 rounded-lg hover:bg-green-400 transition duration-300">
                Download Paper Template (LaTeX)
              </button>
              <button className="w-full bg-colorGreen text-black font-bold py-3 px-6 rounded-lg hover:bg-green-400 transition duration-300">
                Download Copyright Form
              </button>
            </div>
          </div>
        </div>
      </div>

      <Footer />
    </>
  );
};

export default Author;
