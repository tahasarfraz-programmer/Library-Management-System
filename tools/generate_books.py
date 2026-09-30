"""Builds database.sql (schema + 113 books + sample members and loans). Run: python3 tools/generate_books.py"""
import random
DATA = """== Classics
Pride and Prejudice|Jane Austen|1813
Jane Eyre|Charlotte Brontë|1847
Wuthering Heights|Emily Brontë|1847
Great Expectations|Charles Dickens|1861
Moby-Dick|Herman Melville|1851
The Great Gatsby|F. Scott Fitzgerald|1925
To Kill a Mockingbird|Harper Lee|1960
Crime and Punishment|Fyodor Dostoevsky|1866
War and Peace|Leo Tolstoy|1869
Anna Karenina|Leo Tolstoy|1878
Don Quixote|Miguel de Cervantes|1605
Les Misérables|Victor Hugo|1862
The Picture of Dorian Gray|Oscar Wilde|1890
Frankenstein|Mary Shelley|1818
== Fantasy
The Hobbit|J.R.R. Tolkien|1937
The Fellowship of the Ring|J.R.R. Tolkien|1954
The Two Towers|J.R.R. Tolkien|1954
The Return of the King|J.R.R. Tolkien|1955
A Wizard of Earthsea|Ursula K. Le Guin|1968
The Name of the Wind|Patrick Rothfuss|2007
A Game of Thrones|George R.R. Martin|1996
The Lion, the Witch and the Wardrobe|C.S. Lewis|1950
Harry Potter and the Philosopher's Stone|J.K. Rowling|1997
American Gods|Neil Gaiman|2001
Mistborn: The Final Empire|Brandon Sanderson|2006
The Way of Kings|Brandon Sanderson|2010
Good Omens|Terry Pratchett & Neil Gaiman|1990
Alice's Adventures in Wonderland|Lewis Carroll|1865
== Science Fiction
Dune|Frank Herbert|1965
Foundation|Isaac Asimov|1951
I, Robot|Isaac Asimov|1950
Neuromancer|William Gibson|1984
Nineteen Eighty-Four|George Orwell|1949
Brave New World|Aldous Huxley|1932
Fahrenheit 451|Ray Bradbury|1953
The Left Hand of Darkness|Ursula K. Le Guin|1969
Ender's Game|Orson Scott Card|1985
The Martian|Andy Weir|2011
Snow Crash|Neal Stephenson|1992
Hyperion|Dan Simmons|1989
The Time Machine|H.G. Wells|1895
The War of the Worlds|H.G. Wells|1898
== Mystery & Thriller
The Hound of the Baskervilles|Arthur Conan Doyle|1902
A Study in Scarlet|Arthur Conan Doyle|1887
Murder on the Orient Express|Agatha Christie|1934
And Then There Were None|Agatha Christie|1939
The Murder of Roger Ackroyd|Agatha Christie|1926
The Big Sleep|Raymond Chandler|1939
The Maltese Falcon|Dashiell Hammett|1930
Gone Girl|Gillian Flynn|2012
The Girl with the Dragon Tattoo|Stieg Larsson|2005
The Da Vinci Code|Dan Brown|2003
Rebecca|Daphne du Maurier|1938
The Silence of the Lambs|Thomas Harris|1988
In Cold Blood|Truman Capote|1966
Dracula|Bram Stoker|1897
== History
Sapiens|Yuval Noah Harari|2011
Homo Deus|Yuval Noah Harari|2015
Guns, Germs, and Steel|Jared Diamond|1997
Collapse|Jared Diamond|2005
The Rise and Fall of the Third Reich|William L. Shirer|1960
SPQR|Mary Beard|2015
A People's History of the United States|Howard Zinn|1980
The Guns of August|Barbara W. Tuchman|1962
Team of Rivals|Doris Kearns Goodwin|2005
The Silk Roads|Peter Frankopan|2015
1491|Charles C. Mann|2005
Postwar|Tony Judt|2005
The Wager|David Grann|2023
The Diary of a Young Girl|Anne Frank|1947
== Science
A Brief History of Time|Stephen Hawking|1988
Cosmos|Carl Sagan|1980
The Selfish Gene|Richard Dawkins|1976
On the Origin of Species|Charles Darwin|1859
Silent Spring|Rachel Carson|1962
The Structure of Scientific Revolutions|Thomas S. Kuhn|1962
The Elegant Universe|Brian Greene|1999
Astrophysics for People in a Hurry|Neil deGrasse Tyson|2017
The Immortal Life of Henrietta Lacks|Rebecca Skloot|2010
The Gene|Siddhartha Mukherjee|2016
The Emperor of All Maladies|Siddhartha Mukherjee|2010
The Double Helix|James D. Watson|1968
Six Easy Pieces|Richard Feynman|1994
Thinking, Fast and Slow|Daniel Kahneman|2011
== Philosophy
Meditations|Marcus Aurelius|180
The Republic|Plato|-375
Nicomachean Ethics|Aristotle|-340
The Art of War|Sun Tzu|-500
Beyond Good and Evil|Friedrich Nietzsche|1886
Thus Spoke Zarathustra|Friedrich Nietzsche|1883
Being and Time|Martin Heidegger|1927
Critique of Pure Reason|Immanuel Kant|1781
The Prince|Niccolò Machiavelli|1532
Leviathan|Thomas Hobbes|1651
Walden|Henry David Thoreau|1854
The Myth of Sisyphus|Albert Camus|1942
The Stranger|Albert Camus|1942
Man's Search for Meaning|Viktor E. Frankl|1946
Sophie's World|Jostein Gaarder|1991
== Biography
Long Walk to Freedom|Nelson Mandela|1994
Steve Jobs|Walter Isaacson|2011
Einstein: His Life and Universe|Walter Isaacson|2007
Leonardo da Vinci|Walter Isaacson|2017
Becoming|Michelle Obama|2018
Educated|Tara Westover|2018
I Know Why the Caged Bird Sings|Maya Angelou|1969
The Autobiography of Malcolm X|Malcolm X & Alex Haley|1965
Autobiography of a Yogi|Paramahansa Yogananda|1946
Open|Andre Agassi|2009
Born a Crime|Trevor Noah|2016
Wings of Fire|A.P.J. Abdul Kalam|1999
The Story of My Experiments with Truth|M.K. Gandhi|1927
Alexander Hamilton|Ron Chernow|2004
== Classics
Middlemarch|George Eliot|1871
The Adventures of Huckleberry Finn|Mark Twain|1884
The Count of Monte Cristo|Alexandre Dumas|1844
Madame Bovary|Gustave Flaubert|1856
The Brothers Karamazov|Fyodor Dostoevsky|1880
Ulysses|James Joyce|1922
Mrs Dalloway|Virginia Woolf|1925
One Hundred Years of Solitude|Gabriel García Márquez|1967
Things Fall Apart|Chinua Achebe|1958
== Fantasy
The Colour of Magic|Terry Pratchett|1983
The Eye of the World|Robert Jordan|1990
Assassin's Apprentice|Robin Hobb|1995
Jonathan Strange & Mr Norrell|Susanna Clarke|2004
Piranesi|Susanna Clarke|2020
Stardust|Neil Gaiman|1999
The Last Unicorn|Peter S. Beagle|1968
== Science Fiction
The Three-Body Problem|Liu Cixin|2008
Children of Time|Adrian Tchaikovsky|2015
Do Androids Dream of Electric Sheep?|Philip K. Dick|1968
The Dispossessed|Ursula K. Le Guin|1974
Solaris|Stanisław Lem|1961
The Handmaid's Tale|Margaret Atwood|1985
Project Hail Mary|Andy Weir|2021
== Mystery & Thriller
The Talented Mr. Ripley|Patricia Highsmith|1955
The Name of the Rose|Umberto Eco|1980
The Woman in White|Wilkie Collins|1859
Big Little Lies|Liane Moriarty|2014
The No. 1 Ladies' Detective Agency|Alexander McCall Smith|1998
The Thursday Murder Club|Richard Osman|2020
Shutter Island|Dennis Lehane|2003
== History
The Splendid and the Vile|Erik Larson|2020
Empire of the Summer Moon|S.C. Gwynne|2010
The Warmth of Other Suns|Isabel Wilkerson|2010
Salt: A World History|Mark Kurlansky|2002
Bury My Heart at Wounded Knee|Dee Brown|1970
The Making of the Atomic Bomb|Richard Rhodes|1986
Genghis Khan and the Making of the Modern World|Jack Weatherford|2004
== Science
A Short History of Nearly Everything|Bill Bryson|2003
The Sixth Extinction|Elizabeth Kolbert|2014
Surely You're Joking, Mr. Feynman!|Richard Feynman|1985
The Body: A Guide for Occupants|Bill Bryson|2019
Why We Sleep|Matthew Walker|2017
Pale Blue Dot|Carl Sagan|1994
The Hidden Life of Trees|Peter Wohlleben|2015
== Philosophy
The Symposium|Plato|-385
Discourse on Method|René Descartes|1637
Ethics|Baruch Spinoza|1677
Either/Or|Søren Kierkegaard|1843
The Second Sex|Simone de Beauvoir|1949
Tao Te Ching|Laozi|-400
Tractatus Logico-Philosophicus|Ludwig Wittgenstein|1921
== Biography
The Autobiography of Benjamin Franklin|Benjamin Franklin|1791
Elon Musk|Walter Isaacson|2023
Shoe Dog|Phil Knight|2016
Just Kids|Patti Smith|2010
Unbroken|Laura Hillenbrand|2010
Bossypants|Tina Fey|2011
The Glass Castle|Jeannette Walls|2005
== Poetry & Drama
Hamlet|William Shakespeare|1603
Romeo and Juliet|William Shakespeare|1597
Macbeth|William Shakespeare|1623
Othello|William Shakespeare|1622
King Lear|William Shakespeare|1608
A Midsummer Night's Dream|William Shakespeare|1600
The Tempest|William Shakespeare|1623
Antigone|Sophocles|-441
Oedipus Rex|Sophocles|-429
Waiting for Godot|Samuel Beckett|1952
A Doll's House|Henrik Ibsen|1879
Death of a Salesman|Arthur Miller|1949
The Importance of Being Earnest|Oscar Wilde|1895
Leaves of Grass|Walt Whitman|1855
The Waste Land|T.S. Eliot|1922
== Young Adult
The Hunger Games|Suzanne Collins|2008
Divergent|Veronica Roth|2011
The Fault in Our Stars|John Green|2012
The Giver|Lois Lowry|1993
Percy Jackson: The Lightning Thief|Rick Riordan|2005
The Hate U Give|Angie Thomas|2017
Eragon|Christopher Paolini|2002
Holes|Louis Sachar|1998
The Perks of Being a Wallflower|Stephen Chbosky|1999
Anne of Green Gables|L.M. Montgomery|1908
Matilda|Roald Dahl|1988
Charlie and the Chocolate Factory|Roald Dahl|1964
The Outsiders|S.E. Hinton|1967
Wonder|R.J. Palacio|2012
"""
q = lambda s: "'" + s.replace("'", "''") + "'"
def isbn(i):
    d = '978' + '%09d' % random.Random(i * 7919).randrange(10**9)
    return d + str((10 - sum(int(x) * (3 if k % 2 else 1) for k, x in enumerate(d)) % 10) % 10)
rows, cat, i, C = [], '', 0, {}
def PFX(c):
    w = [x for x in c.split() if x[0].isalpha()]
    return ''.join(x[0] for x in w[:2]).upper() if len(w) > 1 else c[:2].upper()
for ln in DATA.strip().splitlines():
    if ln.startswith('== '): cat = ln[3:]; n = C.get(cat, 0); continue
    t, a, y = ln.split('|'); i += 1; n += 1; C[cat] = n; r = random.Random(i); c = r.randint(2, 6)
    rows.append(f"({q(isbn(i))},{q(t)},{q(a)},{q(cat)},{y},{q(PFX(cat) + '-%02d' % (n))},{c},{c})")
sql = """CREATE DATABASE IF NOT EXISTS library_db CHARACTER SET utf8mb4;
USE library_db;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY,role ENUM('admin','student','teacher') NOT NULL DEFAULT 'student',name VARCHAR(80),email VARCHAR(120) UNIQUE,password VARCHAR(255),phone VARCHAR(30),id_no VARCHAR(30) UNIQUE,department VARCHAR(80),level VARCHAR(60),address VARCHAR(200),created TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE books(id INT AUTO_INCREMENT PRIMARY KEY,isbn VARCHAR(20),title VARCHAR(160),author VARCHAR(120),category VARCHAR(40),year SMALLINT,shelf VARCHAR(12),copies INT,available INT,INDEX(category),FULLTEXT(title,author));
CREATE TABLE loans(id INT AUTO_INCREMENT PRIMARY KEY,book_id INT,user_id INT,issued DATE,due DATE,returned DATE NULL,fine DECIMAL(6,2) DEFAULT 0,FOREIGN KEY(book_id) REFERENCES books(id),FOREIGN KEY(user_id) REFERENCES users(id));
CREATE TABLE reservations(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT,book_id INT,status ENUM('pending','approved','rejected','cancelled') DEFAULT 'pending',requested TIMESTAMP DEFAULT CURRENT_TIMESTAMP,decided TIMESTAMP NULL,FOREIGN KEY(user_id) REFERENCES users(id),FOREIGN KEY(book_id) REFERENCES books(id));
INSERT INTO books(isbn,title,author,category,year,shelf,copies,available) VALUES
""" + ",\n".join(rows) + ";\n"
# Sample students/teachers cannot log in (password '!'); real users register on the site.
rg = random.Random(2024)
F = 'Amelia Rohan Sofia Daniel Yuki Grace Hassan Clara Liam Priya Mateo Chloe Omar Ingrid Kenji Zara Lucas Nadia Felix Aisha Tomas Leila Noah Mei'.split()
L = 'Hart Mehta Alvarez Okafor Nakamura Bennett Ali Weber Fischer Nair Costa Dubois Haddad Larsen Tanaka Rahman Moreau Petrov Novak Khan Silva Farah Brooks Lin'.split()
DEP = ['Computer Science','Physics','Literature','History','Mathematics','Biology','Philosophy','Chemistry','Economics','Psychology']
RANKS = ['Lecturer','Senior Lecturer','Associate Professor','Professor']; ST = ['Oak Street','Lake Road','Elm Avenue','Hill Lane','Pine Court','Cedar Drive','Maple Street','Birch Way']
users = [dict(role='teacher' if k % 4 == 2 else 'student', name=f'{F[k]} {L[k]}', id=(f'TC-{1101+k}' if k % 4 == 2 else f'ST-{2201+k}'), dep=DEP[k % 10],
          lvl=RANKS[k % 4] if k % 4 == 2 else f'Year {k % 4 + 1}', addr=f'{10+k*7} {ST[k % 8]}, Springfield') for k in range(24)]
sql += "INSERT INTO users(role,name,email,password,phone,id_no,department,level,address) VALUES\n" + ",\n".join(
    f"({q(u['role'])},{q(u['name'])},{q(u['name'].lower().replace(' ','.')+'@example.com')},'!','555-01{k:02d}',{q(u['id'])},{q(u['dep'])},{q(u['lvl'])},{q(u['addr'])})" for k, u in enumerate(users)) + ";\n"
NB, D = i, lambda n: f"DATE_SUB(CURDATE(),INTERVAL {n} DAY)"
days = lambda u: 30 if users[u]['role'] == 'teacher' else 14
cap = lambda u: 5 if users[u]['role'] == 'teacher' else 3
open_books, active, loans, pairs = set(), {}, [], set()
while len(loans) < 46:
    u, b, openl = rg.randrange(24), rg.randint(1, NB), rg.random() < 0.4
    if (u, b) in pairs: continue
    d = days(u)
    if openl:
        if b in open_books or active.get(u, 0) >= cap(u): continue
        a, r = rg.randint(1, 22), None
    else:
        a = rg.randint(5, 90); r = rg.randint(3, d + 12)
        if r > a: continue
    if openl: open_books.add(b); active[u] = active.get(u, 0) + 1
    pairs.add((u, b)); loans.append((b, u + 1, a, r, d))
sql += "INSERT INTO loans(book_id,user_id,issued,due,returned,fine) VALUES\n" + ",\n".join(
    f"({b},{m},{D(a)},DATE_ADD({D(a)},INTERVAL {d} DAY),{D(a-r) if r is not None else 'NULL'},{max(0, r-d)*0.5 if r is not None else 0})" for b, m, a, r, d in loans) + ";\n"
res = [(u, b, 'approved', f'DATE_SUB(NOW(),INTERVAL {a+1} DAY)', f'DATE_SUB(NOW(),INTERVAL {a} DAY)') for b, u, a, r, d in rg.sample(loans, 14)]
def free():
    while True:
        u, b = rg.randrange(24), rg.randint(1, NB)
        if (u, b) not in pairs and b not in open_books: pairs.add((u, b)); return u + 1, b
for st, cnt in (('pending', 9), ('rejected', 3), ('cancelled', 2)):
    for _ in range(cnt):
        u, b = free(); ago = rg.randint(0, 5) if st == 'pending' else rg.randint(3, 30)
        res.append((u, b, st, f'DATE_SUB(NOW(),INTERVAL {ago} DAY)', 'NULL' if st == 'pending' else f'DATE_SUB(NOW(),INTERVAL {max(ago-1, 0)} DAY)'))
sql += "INSERT INTO reservations(user_id,book_id,status,requested,decided) VALUES\n" + ",\n".join(f"({u},{b},'{st}',{r},{dc})" for u, b, st, r, dc in res) + ";\n"
sql += "UPDATE books b SET available=copies-(SELECT COUNT(*) FROM loans l WHERE l.book_id=b.id AND l.returned IS NULL);\n"
open('database.sql', 'w').write(sql); print(i, 'books,', len(users), 'users,', len(loans), 'loans,', len(res), 'reservations')
