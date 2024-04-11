import React from 'react'

const page = () => {
  const items = [
    {
      img: "https://via.placeholder.com/500x300",
      title: "Izakaya Kotobuki",
      description: "Sushi kotobuki started at East Brisbane as a small restaurant.It has been our great pleasure to serve you since 2008 with the highest quality Japanese food and excellent service at the best prices.On the frontier of Japanese fusion restaurants in Brisbane, we are proud of our contemporary and wonderful cuisine which is selected from the freshest ingredients."
    },
    {
      img: "https://via.placeholder.com/500x300",
      title: "Kodoya japanese Restaurant",
      description: " Kadoya serves Japanese everyday food such as Japanese Chicken curry, Katsudon, karaage Bento box, Ramen and Udon Noodle soup.We have been in Brisbane city for almost 20 years. All staff are from Japan. Our menu starting from $3.95 for entrees and $10.95 for main meals."
    },
    // More items...
  ];

  return (
    
    <div>
      <div className="flex justify-center items-center">
        <div className="text-center">
          <h1 className="text-xl font-bold my-4">
            Meet our sponsors
          </h1>
        </div>
      </div>
      <div className="container mx-auto my-4">
        {items.map((item, index) => (
          <div className="flex flex-wrap mb-4" key={index}>
            <div className="w-full md:w-1/2 p-4">
              <img src={item.img} alt={item.title} className="max-w-full h-auto" />
            </div>
            <div className="w-full md:w-1/2 p-4">
              <h3 className="text-lg font-semibold">{item.title}</h3>
              <p className="my-2">{item.description}</p>
            
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default page
