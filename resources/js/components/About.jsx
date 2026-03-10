import React from "react";
import { Link } from "@inertiajs/react";
import { ArrowRight, CheckCircle } from 'lucide-react';

const About = () => {
    return (
        <div>
            <div className="h-screen flex justify-center relative items-center font-spaceGrotesk m-5">
                <div className="flex flex-col md:flex-row gap-6 md:gap-0">
                    <div className="text-white flex flex-col gap-10 md:w-7/12">
                        <div className="flex flex-col gap-3">
                            <h1 className="text-white text-4xl font-bold">About Conference</h1>
                            <p className="font-plusJakarta font-medium bg-gradient-to-b from-white to-[#181818] text-transparent bg-clip-text md:w-10/12">
                                The development of science and technology produces global changes in various aspects of life. The prosperity of a nation does not only come from physical natural resources but also human resources in the form of intellectual capital, social capital, and credibility. The demand to improve the quality of education is a necessity in today's industry.
                            </p>
                            <Link href="/information">
                                <button className="flex items-center gap-3 bg-colorGreen px-5 py-2 font-bold rounded-full w-fit text-lg text-black hover:bg-[#181818] hover:text-colorGreen border border-colorGreen hover:border-colorGreen transition">
                                    Read Information <ArrowRight size={20}/>
                                </button>
                            </Link>
                        </div>
                        <div className="flex flex-col gap-3">
                            <h1 className="text-white text-2xl font-bold">Submission</h1>
                            <p className="font-plusJakarta text-gray-400 md:w-10/12">We invite you to submit your research work to our conference, where accepted papers will have the opportunity to be published in prestigious journals. The Journal of Science and Applicative Technology (JSAT), a SINTA 3 accredited journal. Additionally, submissions are open for the Journal of Multidisciplinary Applied Natural Science, a Q2-ranked journal.</p>
                        </div>
                    </div>
                    <div className="order-first md:w-1/2 md:order-last flex items-center justify-center">
                        <div className="bg-gradient-to-br from-colorGreen to-[#181818] p-8 rounded-lg w-full h-64 md:h-auto flex items-center justify-center">
                            <p className="text-white text-center text-lg font-spaceGrotesk">Conference Image</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default About
