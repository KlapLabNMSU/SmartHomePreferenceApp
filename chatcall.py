import sys
import openai

userPrompt = str(sys.argv[1])

actualPrompt = """I am running a smart home system using open hab, and I am expanding its funcitonality further. I have written a schedule builder that takes in user preferences like, 
\"I don't want the blender an the microwave to be turned on at the same time, and the microwave should have priority.\" This would be expressed as \"1>2\". This is called a relational preference, where "1" is the device number of the microwave
and "2" is the device number of the blender, and the ">" indicates that device 1 has priority. I am going to give you a sentence similar to the one I gave as an example, where I indicate the priority of one
device over another, and you are to return to me the relational preference that corresponds to the sentence I have given you. Do not say any other text, only the relational preference. """ + userPrompt
apiKey = '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'

openai.api_key = apiKey

conversation = [

    {"role": "system", "content": "You are a helpful assistant"},

    {"role": "user", "content": actualPrompt}

]

response = openai.ChatCompletion.create(
    model="gpt-3.5-turbo",
    messages=conversation,
    max_tokens = 20,
)

print(response['choices'][0]["message"]["content"])