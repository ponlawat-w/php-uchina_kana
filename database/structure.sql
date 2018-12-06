create table words
(
  id       int auto_increment
    primary key,
  word     varchar(500) not null,
  type     varchar(100) not null,
  meaning  text         not null
)
  engine = InnoDB;

create table synonyms
(
  id      int auto_increment
    primary key,
  word    int          not null,
  synonym varchar(500) not null,
  constraint synonyms_words_id_fk
  foreign key (word) references words (id)
    on update cascade
    on delete cascade
)
  engine = InnoDB;

create index synonyms_synonym_index
  on synonyms (synonym);

create index synonyms_words_id_fk
  on synonyms (word);

create index words_word_index
  on words (word);

