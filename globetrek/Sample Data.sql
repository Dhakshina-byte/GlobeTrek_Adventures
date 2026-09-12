
USE globetrek_db;


INSERT INTO users (user_id, name, email, password, phone, role) VALUES
(2, 'Nadeesha Perera',  'nadeesha@globetrek.com',
   '$2y$10$7crkK6HEiYvBxxZ.P9CpE.gl6c9o8loB70aRDkXpclwTVq3hf2p0q', '0771234567', 'staff'),
(3, 'Kasun Fernando',   'kasun@globetrek.com',
   '$2y$10$7crkK6HEiYvBxxZ.P9CpE.gl6c9o8loB70aRDkXpclwTVq3hf2p0q', '0772345678', 'staff'),
(4, 'Jane Doe',         'jane.doe@example.com',
   '$2y$10$7crkK6HEiYvBxxZ.P9CpE.gl6c9o8loB70aRDkXpclwTVq3hf2p0q', '0711111111', 'customer'),
(5, 'Michael Smith',    'michael.smith@example.com',
   '$2y$10$7crkK6HEiYvBxxZ.P9CpE.gl6c9o8loB70aRDkXpclwTVq3hf2p0q', '0712222222', 'customer'),
(6, 'Amara Wickrama',   'amara.w@example.com',
   '$2y$10$7crkK6HEiYvBxxZ.P9CpE.gl6c9o8loB70aRDkXpclwTVq3hf2p0q', '0713333333', 'customer'),
(7, 'Liam O''Connor',   'liam.oconnor@example.com',
   '$2y$10$7crkK6HEiYvBxxZ.P9CpE.gl6c9o8loB70aRDkXpclwTVq3hf2p0q', '0714444444', 'customer'),
(8, 'Priya Nair',       'priya.nair@example.com',
   '$2y$10$7crkK6HEiYvBxxZ.P9CpE.gl6c9o8loB70aRDkXpclwTVq3hf2p0q', '0715555555', 'customer');


INSERT INTO packages
  (package_id, title, destination, description, activities, price, duration_days, duration_nights, created_by) VALUES
(1, 'Ancient Cities & Hill Country', 'Sigiriya, Kandy, Nuwara Eliya',
   'A week through Sri Lanka''s cultural heartland — Sigiriya rock fortress, the sacred city of Kandy, and cool hill-country tea estates around Nuwara Eliya, with a private driver throughout.',
   'Sigiriya rock climb\nDambulla cave temple\nKandy Temple of the Tooth\nTea factory tour\nTrain ride through the hills',
   690.00, 7, 6, 2),
(2, 'Southern Coast Escape', 'Galle, Mirissa, Unawatuna',
   'A relaxed five-day trip along the southern coast — the Dutch fort at Galle, whale watching off Mirissa, and beach time in Unawatuna.',
   'Galle Fort walking tour\nWhale and dolphin watching\nSnorkelling\nBeach days',
   450.00, 5, 4, 2),
(3, 'Yala & Udawalawe Safari', 'Yala National Park, Udawalawe',
   'A four-day wildlife trip with a private tracker, chasing leopard sightings in Yala and elephant herds in Udawalawe.',
   'Yala leopard safari (2 drives)\nUdawalawe elephant safari\nElephant Transit Home visit',
   520.00, 4, 3, 3),
(4, 'Grand Sri Lanka Tour', 'Colombo, Sigiriya, Kandy, Ella, Galle',
   'The full ten-day circuit — cultural triangle, hill country, and the south coast — for travellers who want to see it all in one trip.',
   'Sigiriya rock climb\nKandy Temple of the Tooth\nEla Nine Arch Bridge\nGalle Fort\nBeach day in Mirissa',
   980.00, 10, 9, 2),
(5, 'Ella Hill Escape', 'Ella, Nuwara Eliya',
   'A short, scenic hill-country break centred on Ella — waterfalls, tea country and one of the world''s most photographed train rides.',
   'Nine Arch Bridge\nLittle Adam''s Peak hike\nRavana Falls\nScenic train to Kandy',
   340.00, 3, 2, 3),
(6, 'Northern Heritage Trail', 'Jaffna, Mannar',
   'An off-the-beaten-path trip through Sri Lanka''s north, covering Jaffna''s Tamil heritage sites and the causeway out to Mannar.',
   'Jaffna Fort\nNallur Kandaswamy Temple\nMannar causeway and baobab tree\nLocal seafood tasting',
   410.00, 4, 3, 2);


INSERT INTO bookings
  (booking_id, user_id, package_id, travel_date, travelers, special_requests, total_price, status, payment_status) VALUES
(1, 4, 1, '2026-12-10', 2, NULL,                              1380.00, 'confirmed', 'paid'),
(2, 4, 2, '2027-01-22', 1, NULL,                               450.00, 'pending',   'unpaid'),
(3, 5, 3, '2026-11-05', 4, 'Vegetarian meals please',         2080.00, 'confirmed', 'paid'),
(4, 6, 4, '2027-02-14', 2, 'Honeymoon — late checkout if possible', 1960.00, 'pending',   'unpaid'),
(5, 7, 5, '2026-10-18', 3, NULL,                              1020.00, 'confirmed', 'paid'),
(6, 8, 1, '2027-03-01', 2, NULL,                              1380.00, 'cancelled', 'unpaid');

-- ---------- Payments (for the paid bookings above) ----------INSERT INTO payments (payment_id, booking_id, amount, method, card_holder, card_last4) VALUES
(1, 1, 1380.00, 'card', 'Jane Doe',        '4242'),
(2, 3, 2080.00, 'card', 'Michael Smith',   '1881'),
(3, 5, 1020.00, 'card', 'Liam O''Connor',  '0005');


INSERT INTO queries (query_id, user_id, name, email, subject, message, reply, replied_by, status) VALUES
(1, 4, 'Jane Doe',      'jane.doe@example.com',
   'Question about the hill country tour',
   'Does the Ancient Cities & Hill Country package include the train ride, or is that an extra cost?',
   'Hi Jane, the scenic train segment is included in the package price — no extra cost. Looking forward to hosting you!',
   2, 'answered'),
(2, NULL, 'Robert Chan', 'robert.chan@example.com',
   'Group booking for 8 people',
   'We''re a group of 8 interested in the Grand Sri Lanka Tour in April. Can you accommodate a group that size?',
   NULL, NULL, 'open'),
(3, 6, 'Amara Wickrama', 'amara.w@example.com',
   'Dietary requirements',
   'I have a shellfish allergy — can this be flagged for the safari package meals?',
   NULL, NULL, 'open'),
(4, NULL, 'Sofia Rossi', 'sofia.rossi@example.com',
   'Payment method question',
   'Do you accept payment in Euros, or only USD?',
   'Hi Sofia, payments are processed in USD only at the moment. Thanks for checking!',
   3, 'answered');


ALTER TABLE users     AUTO_INCREMENT = 9;
ALTER TABLE packages  AUTO_INCREMENT = 7;
ALTER TABLE bookings  AUTO_INCREMENT = 7;
ALTER TABLE payments  AUTO_INCREMENT = 4;
ALTER TABLE queries   AUTO_INCREMENT = 5;