import pandas as pd
import nltk
import mysql.connector
from nltk.corpus import opinion_lexicon
from nltk.tokenize import word_tokenize
from translate import Translator

# Pastikan Anda telah mendownload resources
# nltk.download('opinion_lexicon')
# nltk.download('punkt')

# Buat koneksi ke database
mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    passwd="",
    database="analisis-sentiment"
)

# Membuat cursor
cursor_data_clean = mydb.cursor(buffered=True)
cursor_sentiment_positive = mydb.cursor(buffered=True)
cursor_sentiment_negative = mydb.cursor(buffered=True)

# Query untuk mendapatkan data dari tabel proses
query_data_clean = "SELECT id, real_text, clean_text FROM preprocessings"
cursor_data_clean.execute(query_data_clean)

query_sentiment_positive = "SELECT id, kata FROM sentiment_positif"
cursor_sentiment_positive.execute(query_sentiment_positive)

query_sentiment_negative = "SELECT id, kata FROM sentiment_negatif"
cursor_sentiment_negative.execute(query_sentiment_negative)

# Mengambil semua data dari hasil query
data_clean = cursor_data_clean.fetchall()
data_sentiment_positive = cursor_sentiment_positive.fetchall()
data_sentiment_negative = cursor_sentiment_negative.fetchall()

# Membuat DataFrame dari data yang diambil
df_clean = pd.DataFrame(data_clean, columns=['id', 'real_text', 'clean_text'])
df_positif = pd.DataFrame(data_sentiment_positive, columns=['id', 'kata'])
df_negatif = pd.DataFrame(data_sentiment_negative, columns=['id', 'kata'])

# Label
positive_words = set(df_positif['kata'])
negative_words = set(df_negatif['kata'])

# function label sentiment
def label_sentiment(text):
    words = word_tokenize(text.lower())
    positive_count = sum(1 for word in words if word in positive_words)
    negative_count = sum(1 for word in words if word in negative_words)
    
    if positive_count > negative_count:
        return 'positif'
    elif negative_count > positive_count:
        return 'negatif'
    else:
        return 'netral'

# Melabeli data
df_clean['sentiment'] = df_clean['clean_text'].apply(label_sentiment)

# Menyimpan hasil labeling kembali ke database
for index, row in df_clean.iterrows():
    insert_query = """
        INSERT INTO labelling (id, real_text, clean_text, sentiment) 
        VALUES (%s, %s, %s, %s) 
        ON DUPLICATE KEY UPDATE 
            real_text = VALUES(real_text), 
            clean_text = VALUES(clean_text), 
            sentiment = VALUES(sentiment)
        """
    cursor_data_clean.execute(insert_query, (row['id'], row['real_text'], row['clean_text'], row['sentiment']))


# Commit perubahan ke database
mydb.commit()

# Menutup cursor dan koneksi
cursor_data_clean.close()
cursor_sentiment_positive.close()
cursor_sentiment_negative.close()
mydb.close()

# print("Labeling selesai dan hasil disimpan ke tabel labelling di database")