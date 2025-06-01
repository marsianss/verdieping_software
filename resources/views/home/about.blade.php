<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('About Us') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-2xl font-bold text-cyan-700 mb-4">Our Story</h3>
                            <p class="mb-4">
                                Windkracht-12 was founded in 2010 by a group of passionate kitesurfers who wanted to share their love for the sport with others. Starting with just a few kites and a small beach hut, we've grown to become one of the most respected kitesurfing schools in the Netherlands.
                            </p>
                            <p class="mb-4">
                                Our name "Windkracht-12" refers to the highest level on the Beaufort wind force scale, symbolizing our enthusiasm and energy for kitesurfing. While we don't actually teach in force 12 winds (that would be a hurricane!), it represents our passion for the sport in all safe conditions.
                            </p>
                            <p>
                                Located on the beautiful beaches of the Dutch coast, we benefit from ideal wind conditions and safe learning environments for kitesurfers of all levels.
                            </p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h3 class="text-2xl font-bold text-cyan-700 mb-4">Our Mission</h3>
                            <p class="mb-4">
                                At Windkracht-12, our mission is to provide safe, fun, and effective kitesurfing instruction for riders of all levels. We're committed to:
                            </p>
                            <ul class="list-disc pl-5 mb-4 space-y-2">
                                <li>Teaching proper kitesurfing techniques with a focus on safety</li>
                                <li>Creating an inclusive environment where everyone feels welcome</li>
                                <li>Promoting environmental stewardship and respect for the ocean</li>
                                <li>Fostering a community of kitesurfing enthusiasts</li>
                                <li>Continuously improving our teaching methods and equipment</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-10">
                        <h3 class="text-2xl font-bold text-cyan-700 mb-4">Meet Our Team</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <div class="text-center">
                                <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-4">
                                    <img src="/images/team/instructor1.jpg" alt="Jan Instructeur" class="w-full h-full object-cover">
                                </div>
                                <h4 class="text-lg font-semibold">Jan Instructeur</h4>
                                <p class="text-gray-600">Head Instructor</p>
                                <p class="mt-2 text-sm text-gray-500">15+ years of kitesurfing experience, IKO Level 3 Instructor. Specializes in beginner and intermediate instruction.</p>
                            </div>
                            <div class="text-center">
                                <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-4">
                                    <img src="/images/team/instructor2.jpg" alt="Lisa Leraar" class="w-full h-full object-cover">
                                </div>
                                <h4 class="text-lg font-semibold">Lisa Leraar</h4>
                                <p class="text-gray-600">Senior Instructor</p>
                                <p class="mt-2 text-sm text-gray-500">Former professional kitesurfer with 10+ years of teaching experience. Expert in advanced techniques and wave riding.</p>
                            </div>
                            <div class="text-center">
                                <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-4">
                                    <img src="/images/team/instructor3.jpg" alt="Admin User" class="w-full h-full object-cover">
                                </div>
                                <h4 class="text-lg font-semibold">Admin User</h4>
                                <p class="text-gray-600">Owner & Operations Manager</p>
                                <p class="mt-2 text-sm text-gray-500">Founded Windkracht-12 in 2010. Oversees all operations and ensures the highest quality instruction and equipment.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10">
                        <h3 class="text-2xl font-bold text-cyan-700 mb-4">Our Location</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <p class="mb-4">
                                    Our kitesurfing school is located at Strandweg 1, Den Haag, right on the beautiful sandy beaches of Scheveningen. This location offers ideal conditions for learning with consistent winds and safe, shallow waters.
                                </p>
                                <div class="mt-4">
                                    <h4 class="font-semibold mb-2">How to Find Us:</h4>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>By car: Parking available at Zwarte Pad parking area</li>
                                        <li>By public transport: Tram 9 to Scheveningen Nord</li>
                                        <li>Look for our blue and white flags on the beach</li>
                                    </ul>
                                </div>
                                <div class="mt-6">
                                    <h4 class="font-semibold mb-2">Office Hours:</h4>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Monday - Friday: 9:00 AM - 5:00 PM</li>
                                        <li>Saturday & Sunday: 8:00 AM - 7:00 PM (seasonal)</li>
                                    </ul>
                                    <p class="mt-4 text-sm text-gray-600 italic">
                                        Our administrative office is located above our beachfront school. For general inquiries, equipment rental, or lesson bookings, please visit us during office hours.
                                    </p>
                                </div>
                            </div>
                            <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                                <div class="h-64 w-full rounded-lg overflow-hidden mb-4">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2452.3555953525384!2d4.2770331!3d52.1129333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5b153156d857d%3A0xf249a33902a1668!2sStrandweg%201%2C%202586%20JK%20Den%20Haag!5e0!3m2!1sen!2snl!4v1716771600000!5m2!1sen!2snl"
                                        width="100%"
                                        height="100%"
                                        style="border:0;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                </div>
                                <div class="flex items-center space-x-2 text-gray-600 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>Phone: +31 70 123 4567</span>
                                </div>
                                <div class="flex items-center space-x-2 text-gray-600 text-sm mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span>Email: info@windkracht12.nl</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
