<template>
  <div class="min-h-screen bg-white">
    <!-- Navbar -->
    <nav
      :class="[
        'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
        isScrolled ? 'bg-white shadow-lg' : 'bg-white/95 backdrop-blur-sm'
      ]"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          <!-- Logo -->
          <div class="flex items-center space-x-2">
            <span class="text-2xl font-bold">
              <span class="text-red-600">PRIME</span>
              <span class="text-gray-800">OUTSOURCING</span>
            </span>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden lg:flex items-center space-x-8">
            <a
              v-for="link in navLinks"
              :key="link.href"
              :href="link.href"
              class="text-gray-700 hover:text-red-600 font-medium transition-colors duration-200"
            >
              {{ link.label }}
            </a>
<a
              href="/register/client"
              class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-semibold transition-all duration-200 hover:shadow-lg hover:scale-105 inline-flex items-center justify-center"
            >
              Get Started (Client)
            </a>
          </div>

          <!-- Mobile Menu Button -->
          <button
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <Menu v-if="!isMobileMenuOpen" class="w-6 h-6 text-gray-700" />
            <X v-else class="w-6 h-6 text-gray-700" />
          </button>
        </div>

        <!-- Mobile Navigation -->
        <Transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0 -translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition duration-150 ease-in"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-2"
        >
          <div
            v-if="isMobileMenuOpen"
            class="lg:hidden bg-white border-t border-gray-100 py-4"
          >
            <div class="flex flex-col space-y-4">
              <a
                v-for="link in navLinks"
                :key="link.href"
                :href="link.href"
                class="text-gray-700 hover:text-red-600 font-medium px-4 py-2 transition-colors"
                @click="isMobileMenuOpen = false"
              >
                {{ link.label }}
              </a>
              <div class="px-4 pt-2">
                <button
                  class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200"
                >
                  Get Started
                </button>
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </nav>

    <!-- Hero Section -->
    <section
      id="home"
      class="pt-20 min-h-screen flex items-center bg-gradient-to-br from-gray-50 via-white to-red-50 relative overflow-hidden"
    >
      <!-- Background Decorations -->
      <div class="absolute top-20 right-0 w-96 h-96 bg-red-100 rounded-full blur-3xl opacity-40"></div>
      <div class="absolute bottom-0 left-0 w-80 h-80 bg-red-200 rounded-full blur-3xl opacity-30"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div class="space-y-8">
            <div class="inline-flex items-center space-x-2 bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-medium">
              <Sparkles class="w-4 h-4" />
              <span>19+ Years of Excellence</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
              Cut Your Business Costs and
              <span class="text-red-600">Boost Your ROI</span>
            </h1>

            <p class="text-xl text-gray-600 leading-relaxed max-w-xl">
              Reduce business costs by up to 70% and maximize your profit margins with our skilled offshore Filipino talent. We help global businesses grow smarter through reliable, cost-effective BPO and IT solutions.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
              <button
                class="group bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 hover:shadow-xl hover:shadow-red-200 flex items-center justify-center space-x-2"
              >
                <span>Get a Free Quote</span>
                <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
              </button>
              <button
                class="border-2 border-gray-300 hover:border-red-600 text-gray-700 hover:text-red-600 px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 flex items-center justify-center space-x-2"
              >
                <Play class="w-5 h-5" />
                <span>Watch Video</span>
              </button>
            </div>

            <!-- Trust Badges -->
            <div class="flex items-center space-x-8 pt-4">
              <div class="flex items-center space-x-2">
                <div class="flex -space-x-2">
                  <div class="w-10 h-10 rounded-full bg-red-200 border-2 border-white flex items-center justify-center">
                    <Users class="w-5 h-5 text-red-600" />
                  </div>
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-900">500+</p>
                  <p class="text-xs text-gray-500">Happy Clients</p>
                </div>
              </div>
              <div class="h-10 w-px bg-gray-200"></div>
              <div class="flex items-center space-x-2">
                <Star class="w-5 h-5 text-yellow-500 fill-yellow-500" />
                <div>
                  <p class="text-sm font-semibold text-gray-900">4.9/5</p>
                  <p class="text-xs text-gray-500">Client Rating</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Hero Image/Illustration -->
          <div class="relative hidden lg:block">
            <div class="relative z-10">
              <div class="w-full h-[500px] bg-gradient-to-br from-red-500 to-red-600 rounded-3xl flex items-center justify-center overflow-hidden">
                <div class="text-center text-white p-8">
                  <div class="w-32 h-32 bg-white/20 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <Building2 class="w-16 h-16 text-white" />
                  </div>
                  <h3 class="text-2xl font-bold mb-2">Your Offshore Partner</h3>
                  <p class="text-white/80">Manila, Philippines</p>
                </div>
              </div>
            </div>
            <!-- Floating Elements -->
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-red-600 rounded-full"></div>
            <div class="absolute top-1/2 -right-8 w-16 h-16 bg-red-400 rounded-full"></div>
            <div class="absolute -bottom-4 left-1/4 w-20 h-20 bg-red-300 rounded-full"></div>
          </div>
        </div>
      </div>

      <!-- Scroll Indicator -->
      <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <ChevronDown class="w-8 h-8 text-red-600" />
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-red-600 relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-red-700 via-red-600 to-red-700"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <div
            v-for="stat in stats"
            :key="stat.label"
            class="text-center"
          >
            <div class="text-4xl sm:text-5xl font-bold text-white mb-2">{{ stat.value }}</div>
            <div class="text-red-100 font-medium">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 lg:py-32 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <div class="inline-flex items-center space-x-2 bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
            <Layers class="w-4 h-4" />
            <span>Our Services</span>
          </div>
          <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
            Outsourcing Solutions <span class="text-red-600">for You!</span>
          </h2>
          <p class="text-xl text-gray-600">
            Expand your business through strategic outsourcing. We specialize in offshore staff leasing, seat leasing, comprehensive back office services, and an array of additional solutions tailored to your needs.
          </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="service in services"
            :key="service.title"
            class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-red-100 hover:-translate-y-1"
          >
            <div
              class="w-14 h-14 rounded-xl flex items-center justify-center mb-6 transition-all duration-300"
              :class="[service.bgColor, 'group-hover:scale-110']"
            >
              <component :is="service.icon" class="w-7 h-7 text-red-600" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-red-600 transition-colors">
              {{ service.title }}
            </h3>
            <p class="text-gray-600 leading-relaxed mb-4">
              {{ service.description }}
            </p>
            <a
              href="#contact"
              class="inline-flex items-center text-red-600 font-semibold hover:text-red-700 transition-colors group/link"
            >
              Learn More
              <ArrowRight class="w-4 h-4 ml-2 group-hover/link:translate-x-1 transition-transform" />
            </a>
          </div>
        </div>

        <div class="text-center mt-12">
          <button
            class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 hover:shadow-xl hover:shadow-red-200 inline-flex items-center space-x-2"
          >
            <span>Outsource With Us</span>
            <ArrowRight class="w-5 h-5" />
          </button>
        </div>
      </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 lg:py-32 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
          <div>
            <div class="inline-flex items-center space-x-2 bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
              <Award class="w-4 h-4" />
              <span>Why Choose Us</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
              Your Trusted <span class="text-red-600">BPO Partner</span>
            </h2>
            <p class="text-xl text-gray-600 mb-8">
              Since day one, our focus has been simple: deliver reliable, cost-effective BPO and IT solutions that let our clients do more, faster. Whether you need developers, marketers, or support staff, we tailor every setup to fit your goals.
            </p>

            <div class="space-y-6">
              <div
                v-for="benefit in benefits"
                :key="benefit.title"
                class="flex items-start space-x-4"
              >
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                  <component :is="benefit.icon" class="w-6 h-6 text-red-600" />
                </div>
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ benefit.title }}</h4>
                  <p class="text-gray-600">{{ benefit.description }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="relative">
            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-4">
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white">
                  <Zap class="w-8 h-8 mb-4" />
                  <h4 class="text-lg font-semibold mb-2">Fast Deployment</h4>
                  <p class="text-red-100 text-sm">Get your team up and running within days</p>
                </div>
                <div class="bg-gray-100 rounded-2xl p-6">
                  <Shield class="w-8 h-8 text-red-600 mb-4" />
                  <h4 class="text-lg font-semibold text-gray-900 mb-2">Secure & Reliable</h4>
                  <p class="text-gray-600 text-sm">Enterprise-grade security protocols</p>
                </div>
              </div>
              <div class="space-y-4 pt-8">
                <div class="bg-gray-100 rounded-2xl p-6">
                  <DollarSign class="w-8 h-8 text-red-600 mb-4" />
                  <h4 class="text-lg font-semibold text-gray-900 mb-2">Cost Effective</h4>
                  <p class="text-gray-600 text-sm">Save up to 70% on operational costs</p>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white">
                  <HeartHandshake class="w-8 h-8 mb-4" />
                  <h4 class="text-lg font-semibold mb-2">Long-term Partner</h4>
                  <p class="text-red-100 text-sm">Building lasting relationships</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Process Section -->
    <section class="py-20 lg:py-32 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <div class="inline-flex items-center space-x-2 bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
            <Settings class="w-4 h-4" />
            <span>How It Works</span>
          </div>
          <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
            Simple <span class="text-red-600">4-Step Process</span>
          </h2>
          <p class="text-xl text-gray-600">
            Getting started with Prime Outsourcing is easy. Our streamlined process ensures you get the best talent quickly.
          </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div
            v-for="(step, index) in processSteps"
            :key="step.title"
            class="relative"
          >
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 h-full">
              <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center font-bold text-xl mb-6">
                {{ index + 1 }}
              </div>
              <h3 class="text-xl font-bold text-gray-900 mb-3">{{ step.title }}</h3>
              <p class="text-gray-600">{{ step.description }}</p>
            </div>
            <!-- Connector Line -->
            <div
              v-if="index < 3"
              class="hidden lg:block absolute top-1/2 -right-4 w-8 h-0.5 bg-red-200"
            ></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 lg:py-32 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <div class="inline-flex items-center space-x-2 bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
            <MessageSquare class="w-4 h-4" />
            <span>Testimonials</span>
          </div>
          <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
            What Our <span class="text-red-600">Clients Say</span>
          </h2>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="testimonial in testimonials"
            :key="testimonial.name"
            class="bg-gray-50 rounded-2xl p-8 border border-gray-100"
          >
            <div class="flex items-center space-x-1 mb-4">
              <Star
                v-for="i in 5"
                :key="i"
                class="w-5 h-5 text-yellow-500 fill-yellow-500"
              />
            </div>
            <p class="text-gray-700 mb-6 leading-relaxed italic">
              "{{ testimonial.quote }}"
            </p>
            <div class="flex items-center space-x-4">
              <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <User class="w-6 h-6 text-red-600" />
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">{{ testimonial.name }}</h4>
                <p class="text-sm text-gray-500">{{ testimonial.role }}, {{ testimonial.company }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 lg:py-32 bg-gray-900 text-white relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-red-900/30"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
          <div>
            <div class="inline-flex items-center space-x-2 bg-red-500/20 text-red-400 px-4 py-2 rounded-full text-sm font-medium mb-4">
              <Building class="w-4 h-4" />
              <span>About Us</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-6">
              We Help Global Businesses <span class="text-red-500">Grow Smarter</span>
            </h2>
            <p class="text-xl text-gray-300 mb-8 leading-relaxed">
              Prime Outsourcing is an offshore staff leasing company based in Manila, Philippines, dedicated to resolving your business challenges. Our commitment is to serve enterprises of all sizes, offering comprehensive services that empower you to scale your business.
            </p>
            <p class="text-gray-400 mb-8 leading-relaxed">
              We facilitate seamless and rapid business growth, providing a wide range of solutions, including offshore staff leasing, seat leasing, project outsourcing, virtual captives, and BPO services. Our skilled workforce excels in managing office tasks, administrative functions, web development, graphic design, and more.
            </p>
            <div class="grid grid-cols-2 gap-6">
              <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                <h4 class="text-3xl font-bold text-red-500 mb-1">19+</h4>
                <p class="text-gray-400">Years Experience</p>
              </div>
              <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                <h4 class="text-3xl font-bold text-red-500 mb-1">500+</h4>
                <p class="text-gray-400">Projects Delivered</p>
              </div>
              <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                <h4 class="text-3xl font-bold text-red-500 mb-1">200+</h4>
                <p class="text-gray-400">Expert Team</p>
              </div>
              <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                <h4 class="text-3xl font-bold text-red-500 mb-1">24/7</h4>
                <p class="text-gray-400">Support Available</p>
              </div>
            </div>
          </div>

          <div class="relative">
            <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-3xl p-12 text-center">
              <Globe class="w-24 h-24 mx-auto mb-6 text-white/80" />
              <h3 class="text-2xl font-bold mb-4">Global Reach</h3>
              <p class="text-red-100 mb-6">
                Serving clients across North America, Europe, Australia, and Asia with world-class Filipino talent.
              </p>
              <div class="flex flex-wrap justify-center gap-3">
                <span class="bg-white/10 px-4 py-2 rounded-full text-sm">🇺🇸 USA</span>
                <span class="bg-white/10 px-4 py-2 rounded-full text-sm">🇬🇧 UK</span>
                <span class="bg-white/10 px-4 py-2 rounded-full text-sm">🇦🇺 Australia</span>
                <span class="bg-white/10 px-4 py-2 rounded-full text-sm">🇨🇦 Canada</span>
                <span class="bg-white/10 px-4 py-2 rounded-full text-sm">🇸🇬 Singapore</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 lg:py-32 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <div class="inline-flex items-center space-x-2 bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
            <DollarSign class="w-4 h-4" />
            <span>Pricing</span>
          </div>
          <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
            Flexible <span class="text-red-600">Service Packages</span>
          </h2>
          <p class="text-xl text-gray-600">
            Choose from our range of service packages designed to fit businesses of all sizes. Pay as you go with no hidden fees.
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div
            v-for="plan in pricingPlans"
            :key="plan.name"
            :class="[
              'bg-white rounded-2xl p-8 border-2 transition-all duration-300 hover:shadow-xl',
              plan.featured ? 'border-red-500 shadow-lg scale-105' : 'border-gray-100'
            ]"
          >
            <div v-if="plan.featured" class="bg-red-600 text-white text-sm font-semibold px-4 py-1 rounded-full inline-block mb-4">
              Most Popular
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ plan.name }}</h3>
            <p class="text-gray-600 mb-6">{{ plan.description }}</p>
            <div class="mb-6">
              <span class="text-4xl font-bold text-gray-900">${{ plan.price }}</span>
              <span class="text-gray-500">/month</span>
            </div>
            <ul class="space-y-4 mb-8">
              <li
                v-for="feature in plan.features"
                :key="feature"
                class="flex items-center space-x-3"
              >
                <Check class="w-5 h-5 text-red-600 flex-shrink-0" />
                <span class="text-gray-600">{{ feature }}</span>
              </li>
            </ul>
            <button
              :class="[
                'w-full py-3 rounded-xl font-semibold transition-all duration-300',
                plan.featured
                  ? 'bg-red-600 hover:bg-red-700 text-white'
                  : 'border-2 border-gray-200 hover:border-red-600 text-gray-700 hover:text-red-600'
              ]"
            >
              Get Started
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-red-600 to-red-700 relative overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-1/4 w-64 h-64 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white rounded-full blur-3xl"></div>
      </div>
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
          Ready to Scale Your Business?
        </h2>
        <p class="text-xl text-red-100 mb-8 max-w-2xl mx-auto">
          Join hundreds of businesses that have already transformed their operations with our offshore solutions. Get started today and see the difference.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button
            class="bg-white text-red-600 hover:bg-gray-100 px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 inline-flex items-center justify-center space-x-2"
          >
            <span>Apply as Client</span>
            <ArrowRight class="w-5 h-5" />
          </button>
          <button
            class="border-2 border-white text-white hover:bg-white/10 px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 inline-flex items-center justify-center space-x-2"
          >
            <Phone class="w-5 h-5" />
            <span>Schedule a Call</span>
          </button>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 lg:py-32 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16">
          <div>
            <div class="inline-flex items-center space-x-2 bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
              <Mail class="w-4 h-4" />
              <span>Contact Us</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
              Get In <span class="text-red-600">Touch</span>
            </h2>
            <p class="text-xl text-gray-600 mb-8">
              Have questions about our services? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>

            <div class="space-y-6">
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                  <MapPin class="w-6 h-6 text-red-600" />
                </div>
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 mb-1">Office Address</h4>
                  <p class="text-gray-600">Manila, Philippines</p>
                </div>
              </div>
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                  <Phone class="w-6 h-6 text-red-600" />
                </div>
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 mb-1">Phone Numbers</h4>
                  <p class="text-gray-600">Philippines: 8-805-4990</p>
                  <p class="text-gray-600">Int'l: +63 (977) 835 5080</p>
                </div>
              </div>
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                  <Mail class="w-6 h-6 text-red-600" />
                </div>
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 mb-1">Email Address</h4>
                  <p class="text-gray-600">info@primeoutsourcing.com</p>
                </div>
              </div>
            </div>

            <div class="flex space-x-4 mt-8">
              <a
                href="#"
                class="w-12 h-12 rounded-xl bg-gray-100 hover:bg-red-100 flex items-center justify-center transition-colors"
              >
                <Facebook class="w-5 h-5 text-gray-600 hover:text-red-600" />
              </a>
              <a
                href="#"
                class="w-12 h-12 rounded-xl bg-gray-100 hover:bg-red-100 flex items-center justify-center transition-colors"
              >
                <Linkedin class="w-5 h-5 text-gray-600 hover:text-red-600" />
              </a>
              <a
                href="#"
                class="w-12 h-12 rounded-xl bg-gray-100 hover:bg-red-100 flex items-center justify-center transition-colors"
              >
                <Twitter class="w-5 h-5 text-gray-600 hover:text-red-600" />
              </a>
            </div>
          </div>

          <!-- Contact Form -->
          <div class="bg-gray-50 rounded-2xl p-8 lg:p-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Send Us a Message</h3>
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <div class="grid sm:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                  <input
                    v-model="contactForm.firstName"
                    type="text"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all outline-none"
                    placeholder="John"
                    required
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                  <input
                    v-model="contactForm.lastName"
                    type="text"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all outline-none"
                    placeholder="Doe"
                    required
                  />
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input
                  v-model="contactForm.email"
                  type="email"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all outline-none"
                  placeholder="john@company.com"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                <input
                  v-model="contactForm.company"
                  type="text"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all outline-none"
                  placeholder="Your Company"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Service Interested In</label>
                <select
                  v-model="contactForm.service"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all outline-none bg-white"
                >
                  <option value="">Select a service</option>
                  <option value="staff-leasing">Staff Leasing</option>
                  <option value="seat-leasing">Seat Leasing</option>
                  <option value="web-development">Web Development</option>
                  <option value="digital-marketing">Digital Marketing</option>
                  <option value="call-center">Call Center</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                <textarea
                  v-model="contactForm.message"
                  rows="4"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all outline-none resize-none"
                  placeholder="Tell us about your project..."
                  required
                ></textarea>
              </div>
              <button
                type="submit"
                :disabled="isSubmitting"
                class="w-full bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white py-4 rounded-xl font-semibold text-lg transition-all duration-300 flex items-center justify-center space-x-2"
              >
                <span v-if="!isSubmitting">Send Message</span>
                <span v-else>Sending...</span>
                <Send v-if="!isSubmitting" class="w-5 h-5" />
                <Loader2 v-else class="w-5 h-5 animate-spin" />
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-20 pb-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-12 pb-12 border-b border-gray-800">
          <!-- Company Info -->
          <div class="lg:col-span-2">
            <div class="flex items-center space-x-2 mb-6">
              <span class="text-2xl font-bold">
                <span class="text-red-500">PRIME</span>
                <span class="text-white">OUTSOURCING</span>
              </span>
            </div>
            <p class="text-gray-400 mb-6 max-w-sm leading-relaxed">
              Your trusted offshore staff leasing partner based in Manila, Philippines. We help global businesses grow smarter with skilled Filipino talent.
            </p>
            <div class="flex space-x-4">
              <a
                href="#"
                class="w-10 h-10 rounded-lg bg-gray-800 hover:bg-red-600 flex items-center justify-center transition-colors"
              >
                <Facebook class="w-5 h-5" />
              </a>
              <a
                href="#"
                class="w-10 h-10 rounded-lg bg-gray-800 hover:bg-red-600 flex items-center justify-center transition-colors"
              >
                <Linkedin class="w-5 h-5" />
              </a>
              <a
                href="#"
                class="w-10 h-10 rounded-lg bg-gray-800 hover:bg-red-600 flex items-center justify-center transition-colors"
              >
                <Twitter class="w-5 h-5" />
              </a>
            </div>
          </div>

          <!-- Services -->
          <div>
            <h4 class="text-lg font-semibold mb-6">Services</h4>
            <ul class="space-y-3">
              <li v-for="service in footerServices" :key="service">
                <a href="#services" class="text-gray-400 hover:text-white transition-colors">{{ service }}</a>
              </li>
            </ul>
          </div>

          <!-- Company -->
          <div>
            <h4 class="text-lg font-semibold mb-6">Company</h4>
            <ul class="space-y-3">
              <li v-for="link in footerCompany" :key="link.label">
                <a :href="link.href" class="text-gray-400 hover:text-white transition-colors">{{ link.label }}</a>
              </li>
            </ul>
          </div>

          <!-- Resources -->
          <div>
            <h4 class="text-lg font-semibold mb-6">Resources</h4>
            <ul class="space-y-3">
              <li v-for="link in footerResources" :key="link">
                <a href="#" class="text-gray-400 hover:text-white transition-colors">{{ link }}</a>
              </li>
            </ul>
          </div>
        </div>

        <!-- Bottom Footer -->
        <div class="pt-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
          <p class="text-gray-500 text-sm">
            © 2005-2026 PrimeOutsourcing.com Inc. All Rights Reserved.
          </p>
          <div class="flex space-x-6">
            <a href="#" class="text-gray-500 hover:text-white text-sm transition-colors">Terms of Use</a>
            <a href="#" class="text-gray-500 hover:text-white text-sm transition-colors">Privacy Policy</a>
            <a href="#" class="text-gray-500 hover:text-white text-sm transition-colors">Sitemap</a>
          </div>
        </div>
      </div>
    </footer>

    <!-- Scroll to Top Button -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 scale-90"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-90"
    >
      <button
        v-if="showScrollTop"
        @click="scrollToTop"
        class="fixed bottom-8 right-8 z-50 w-12 h-12 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:shadow-xl"
      >
        <ChevronUp class="w-6 h-6" />
      </button>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import {
  // Navbar
  Menu,
  X,
  // Hero + sections
  ArrowRight,
  Play,
  Users,
  Star,
  ChevronDown,
  ChevronUp,
  Sparkles,
  Building2,
  Building,
  Layers,
  Globe,
  Award,
  Check,
  Zap,
  Shield,
  DollarSign,
  HeartHandshake,
  Settings,
  MessageSquare,
  User,
  Mail,
  MapPin,
  Phone,
  // Services icons
  Code,
  Megaphone,
  Palette,
  FileText,
  Armchair,
  Headphones,
  Calculator,
  UserCog,
  Database,
  // Contact form icons
  Send,
  Loader2,
  // Footer icons
  Facebook,
  Linkedin,
  Twitter,
} from 'lucide-vue-next'

// Reactive state
const isScrolled = ref(false)
const isMobileMenuOpen = ref(false)
const showScrollTop = ref(false)
const isSubmitting = ref(false)

const contactForm = ref({
  firstName: '',
  lastName: '',
  email: '',
  company: '',
  service: '',
  message: '',
})

// Navigation links
const navLinks = [
  { label: 'Home', href: '#home' },
  { label: 'Services', href: '#services' },
  { label: 'About', href: '#about' },
  { label: 'Pricing', href: '#pricing' },
  { label: 'Contact', href: '#contact' },
]

// Stats data
const stats = [
  { value: '19+', label: 'Years Experience' },
  { value: '500+', label: 'Happy Clients' },
  { value: '200+', label: 'Expert Team' },
  { value: '70%', label: 'Cost Savings' },
]

// Services data
const services = [
  {
    icon: Code,
    title: 'Web Development',
    description: 'We build high-quality, customized websites, ensuring a strong online presence and optimal user experience.',
    bgColor: 'bg-red-100',
  },
  {
    icon: Megaphone,
    title: 'Digital Marketing',
    description: 'We craft materials for online optimization, brand visibility, and targeted engagement to meet your business goals.',
    bgColor: 'bg-red-100',
  },
  {
    icon: Palette,
    title: 'Design',
    description: 'We create visually engaging, user-friendly digital assets for lasting impact and engagement.',
    bgColor: 'bg-red-100',
  },
  {
    icon: FileText,
    title: 'Content Writing',
    description: 'We develop useful content to provide your business with relevant information about your products and services.',
    bgColor: 'bg-red-100',
  },
  {
    icon: Armchair,
    title: 'Seat Leasing',
    description: 'We offer a flexible, cost-effective solution for fully-equipped office spaces, enabling efficient scaling.',
    bgColor: 'bg-red-100',
  },
  {
    icon: Headphones,
    title: 'Call Center',
    description: 'We provide a skilled workforce proficient in customer service, enabling you to effectively promote your products and services.',
    bgColor: 'bg-red-100',
  },
  {
    icon: Calculator,
    title: 'Accounting',
    description: "We manage your company's assets and maintain accurate financial records and comprehensive reports.",
    bgColor: 'bg-red-100',
  },
  {
    icon: UserCog,
    title: 'Human Resource',
    description: 'We optimize HR functions, letting you focus on core operations while ensuring talent management and compliance.',
    bgColor: 'bg-red-100',
  },
  {
    icon: Database,
    title: 'Data Processing',
    description: 'We guarantee a seamless processing of company data by organizing and optimizing databases.',
    bgColor: 'bg-red-100',
  },
]

// Benefits data
const benefits = [
  {
    icon: Check,
    title: 'No Hidden Charges',
    description: 'Transparent pricing with no surprise fees or hidden costs.',
  },
  {
    icon: DollarSign,
    title: 'Affordable Rates',
    description: 'Competitive pricing that fits businesses of all sizes.',
  },
  {
    icon: Settings,
    title: 'Customizable Plans',
    description: 'Flexible solutions tailored to your specific needs.',
  },
  {
    icon: Users,
    title: 'Dedicated Staff',
    description: 'Your own team of professionals working exclusively for you.',
  },
]

// Process steps
const processSteps = [
  {
    title: 'Initial Consultation',
    description: 'Tell us about your business needs and we\'ll design a custom solution for you.',
  },
  {
    title: 'Talent Selection',
    description: 'We handpick the best Filipino talent that matches your requirements.',
  },
  {
    title: 'Onboarding',
    description: 'Seamless integration of your new team members into your workflow.',
  },
  {
    title: 'Ongoing Support',
    description: 'Continuous monitoring and support to ensure optimal performance.',
  },
]

// Testimonials
const testimonials = [
  {
    name: 'Michael Chen',
    role: 'CEO',
    company: 'TechFlow Inc.',
    quote: 'Prime Outsourcing transformed our development process. Their Filipino developers are skilled, dedicated, and deliver quality work consistently.',
  },
  {
    name: 'Sarah Williams',
    role: 'Operations Director',
    company: 'Global Retail Co.',
    quote: 'The cost savings have been incredible. We reduced our operational costs by 65% while maintaining the same quality of service.',
  },
  {
    name: 'James Morrison',
    role: 'Founder',
    company: 'StartUp Labs',
    quote: 'Working with Prime Outsourcing has been seamless. Their team feels like an extension of our own company.',
  },
]

// Pricing plans
const pricingPlans = [
  {
    name: 'Starter',
    description: 'Perfect for small businesses',
    price: 100,
    featured: false,
    features: [
      'Part-time staff (20 hrs/week)',
      'Basic support services',
      'Monthly reports',
      'Email support',
      'No setup fee',
    ],
  },
  {
    name: 'Professional',
    description: 'Best for growing companies',
    price: 350,
    featured: true,
    features: [
      'Full-time dedicated staff',
      'All support services',
      'Weekly reports',
      'Priority support',
      'Custom workflows',
      'Team management tools',
    ],
  },
  {
    name: 'Enterprise',
    description: 'For large organizations',
    price: 750,
    featured: false,
    features: [
      'Multiple dedicated staff',
      'All services included',
      'Daily reports',
      '24/7 support',
      'Custom integrations',
      'Dedicated account manager',
    ],
  },
]

// Footer data
const footerServices = [
  'Staff Leasing',
  'Seat Leasing',
  'Web Development',
  'Digital Marketing',
  'Call Center',
  'Back Office',
]

const footerCompany = [
  { label: 'About Us', href: '#about' },
  { label: 'Careers', href: '#' },
  { label: 'Case Studies', href: '#' },
  { label: 'Blog', href: '#' },
  { label: 'Contact', href: '#contact' },
]

const footerResources = [
  'Brochures',
  'FAQs',
  'Testimonials',
  'Videos',
  'Tutorials',
]

// Scroll handler
const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
  showScrollTop.value = window.scrollY > 500
}

// Scroll to top
const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Form submission
const handleSubmit = async () => {
  isSubmitting.value = true

  // Simulate API call
  await new Promise((resolve) => setTimeout(resolve, 2000))

  // Reset form
  contactForm.value = {
    firstName: '',
    lastName: '',
    email: '',
    company: '',
    service: '',
    message: '',
  }

  isSubmitting.value = false
  alert('Thank you for your message! We will get back to you soon.')
}

// Lifecycle hooks
onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}
</style>