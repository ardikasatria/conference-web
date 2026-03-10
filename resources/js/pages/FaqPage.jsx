import React from "react";
import Navbar from "../components/Navbar";
import FAQ from "../components/Faq";
import BannerCustom from "../components/BannerCustom";
import TopicsFaq from "../components/TopicsFaq";

const FaqPage = () => {
    return (
        <>
            <Navbar />
            <BannerCustom name="Information" />
            <div className="flex flex-col items-center m-5 mt-40">
                <div className="text-center md:text-left md:w-7/12 text-white font-spaceGrotesk">
                    <h1 className="text-3xl md:text-4xl font-bold text-white text-center">About Conference</h1>
                    <hr className="text-center m-auto border border-colorGreen w-24"/>
                    <p className="mb-5 mt-5">
                        The development of science and technology brings global changes to various aspects of life. The prosperity of a nation is not solely derived from physical natural resources, but also from human resources, including intellectual capital, social capital, and credibility. Enhancing the quality of education is imperative in today's industry. This conference aims to bring together researchers, scientists, and industry experts to discuss the latest advancements and innovations in science and technology. Join us to exchange ideas, present your research, and network with professionals from around the world.
                    </p>
                    <h2 className="text-green-500 italic text-2xl mb-3">"Building Up The Next Generation of Scientists"</h2>
                    <p>
                    With this in mind, cultivating curiosity and interest in the new generation of scientists is not only crucial for their emotional and intellectual development, but also important to ensure a promising future for scientific exploration and discovery.
                    </p>
                </div>
            </div>
            <TopicsFaq />
            <div className="flex flex-col items-center m-5">
                <h1 className="text-3xl md:text-4xl font-bold text-white text-center mb-5">Frequently Asked Questions</h1>
                <hr className="text-center m-auto border border-colorGreen w-24"/>
            </div>
            <FAQ />
        </>
    );
};

export default FaqPage;
